<?php

namespace App\Services;

use App\Models\NotaFiscal;
use App\Models\Venda;
use App\Models\Emitente;
use App\Models\ItemNfe;
use App\Models\PagamentoNfe;
use App\Models\Produto;
use App\Models\ContasReceber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NFeService
{
    public function gerarNFe($vendaId, $emitenteId, $ambiente = '2')
    {
        try {
            DB::beginTransaction();

            $venda = Venda::with(['items.produto', 'cliente'])->findOrFail($vendaId);
            $emitente = Emitente::findOrFail($emitenteId);

            $numeroNFe = $this->obterProximoNumero($emitenteId);
            $serie = 1;

            $notaFiscal = NotaFiscal::create([
                'venda_id' => $venda->id,
                'emitente_id' => $emitenteId,
                'destinatario_id' => $venda->cliente_id,
                'numero' => $numeroNFe,
                'serie' => $serie,
                'modelo' => '55',
                'tipo_operacao' => '1',
                'finalidade' => '1',
                'natureza_operacao' => 'VENDA',
                'ambiente' => $ambiente,
                'tipo_emissao' => '1',
                'data_emissao' => now(),
                'data_saida_entrada' => $venda->data_venda,
                'status' => 'digitacao',
                'valor_total_produtos' => $venda->valor_total_produtos,
                'valor_desconto' => $venda->valor_desconto,
                'valor_total_nfe' => $venda->valor_total_final,
                'versao_leiaute' => '4.00'
            ]);

            $ordem = 1;
            $valorTotalImpostos = 0;
            
            foreach ($venda->items as $itemVenda) {
                $produto = $itemVenda->produto;
                
                $ncm = $produto->ncm_codigo ?? '02012000';
                $cfop = $produto->cfop ?? '5102';
                $origem = $produto->origem_mercadoria ?? '0';
                
                // Determinar CST/CSOSN
                $cstIcms = $produto->cst_icms ?? ($produto->csosn ?? '102');
                $aliquotaIcms = $produto->aliquota_icms ?? 0;
                
                // Calcular valores
                $valorTotalItem = $itemVenda->quantidade * $itemVenda->preco_unitario;
                $baseCalculoIcms = $valorTotalItem;
                $valorIcms = ($baseCalculoIcms * $aliquotaIcms) / 100;
                
                $aliquotaPis = $produto->aliquota_pis ?? 0;
                $baseCalculoPis = $valorTotalItem;
                $valorPis = ($baseCalculoPis * $aliquotaPis) / 100;
                
                $aliquotaCofins = $produto->aliquota_cofins ?? 0;
                $baseCalculoCofins = $valorTotalItem;
                $valorCofins = ($baseCalculoCofins * $aliquotaCofins) / 100;
                
                $cstPis = '01'; // Operação tributável com alíquota zero
                $cstCofins = '01'; // Operação tributável com alíquota zero
                
                if ($aliquotaPis > 0) {
                $cstPis = '01';
                }
                if ($aliquotaCofins > 0) {
                $cstCofins = '01';
                }
                
                $valorTotalImpostos += $valorIcms + $valorPis + $valorCofins;

                ItemNfe::create([
                    'nota_fiscal_id' => $notaFiscal->id,
                    'produto_id' => $produto->id,
                    'ordem' => $ordem++,
                    'codigo_item' => $produto->codigo_barras ?? str_pad($produto->id, 20, '0', STR_PAD_LEFT),
                    'descricao' => $produto->nome,
                    'ncm' => $ncm,
                    'cest' => $produto->cest,
                    'cfop' => $cfop,
                    'unidade' => $produto->unidade_medida,
                    'quantidade' => $itemVenda->quantidade,
                    'valor_unitario' => $itemVenda->preco_unitario,
                    'valor_total' => $valorTotalItem,
                    'desconto_item' => 0,
                    'origem' => $origem,
                    'cst_icms' => $cstIcms,
                    'aliquota_icms' => $aliquotaIcms,
                    'valor_icms' => $valorIcms,
                    'cst_pis' => $cstPis,
                    'aliquota_pis' => $aliquotaPis,
                    'valor_pis' => $valorPis,
                    'cst_cofins' => $cstCofins,
                    'aliquota_cofins' => $aliquotaCofins,
                    'valor_cofins' => $valorCofins,
                    'valor_ipi' => 0
                ]);
            }
            
            // Atualizar valor total de impostos
            $notaFiscal->update(['valor_impostos' => $valorTotalImpostos]);

            $contaReceber = ContasReceber::where('venda_id', $venda->id)->first();
            if ($contaReceber) {
                PagamentoNfe::create([
                    'nota_fiscal_id' => $notaFiscal->id,
                    'forma_pagamento_id' => $contaReceber->forma_pagamento_id,
                    'valor_pagamento' => $venda->valor_total_final,
                    'troco' => 0,
                    'tipo_integracao' => '1'
                ]);
            }

            $chaveAcesso = $this->gerarChaveAcesso($notaFiscal, $emitente);
            
            // Recarregar nota fiscal com relacionamentos para gerar XML
            $notaFiscal->refresh();
            $notaFiscal = NotaFiscal::with(['itens.produto', 'emitente', 'destinatario', 'pagamentos.formaPagamento'])->find($notaFiscal->id);
            
            // Gerar XML e salvar
            $xml = $this->gerarXML($notaFiscal);
            $notaFiscal->update([
                'chave_acesso' => $chaveAcesso,
                'xml_gerado' => $xml
            ]);

            DB::commit();

            return $notaFiscal;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao gerar NFe: ' . $e->getMessage());
            throw $e;
        }
    }

    private function obterProximoNumero($emitenteId)
    {
        $ultimaNota = NotaFiscal::where('emitente_id', $emitenteId)
            ->orderBy('numero', 'desc')
            ->first();

        return $ultimaNota ? $ultimaNota->numero + 1 : 1;
    }

    private function gerarChaveAcesso($notaFiscal, $emitente)
    {
        $uf = $this->getCodigoUF($emitente->uf);
        $anoMes = $notaFiscal->data_emissao->format('ym');
        $cnpj = preg_replace('/[^0-9]/', '', $emitente->cnpj);
        $modelo = str_pad($notaFiscal->modelo, 2, '0', STR_PAD_LEFT);
        $serie = str_pad($notaFiscal->serie, 3, '0', STR_PAD_LEFT);
        $numero = str_pad($notaFiscal->numero, 9, '0', STR_PAD_LEFT);
        $tipoEmissao = $notaFiscal->tipo_emissao;
        $codigoNumerico = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);

        $chave = $uf . $anoMes . $cnpj . $modelo . $serie . $numero . $tipoEmissao . $codigoNumerico;

        $digitoVerificador = $this->calcularDigitoVerificador($chave);

        return $chave . $digitoVerificador;
    }

    private function calcularDigitoVerificador($chave)
    {
        $soma = 0;
        $multiplicadores = [4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < strlen($chave); $i++) {
            $soma += intval($chave[$i]) * $multiplicadores[$i];
        }

        $resto = $soma % 11;
        $digito = 11 - $resto;

        if ($digito >= 10) {
            $digito = 0;
        }

        return $digito;
    }

    private function getCodigoUF($uf)
    {
        $codigos = [
            'AC' => '12', 'AL' => '27', 'AP' => '16', 'AM' => '13', 'BA' => '29',
            'CE' => '23', 'DF' => '53', 'ES' => '32', 'GO' => '52', 'MA' => '21',
            'MT' => '51', 'MS' => '50', 'MG' => '31', 'PA' => '15', 'PB' => '25',
            'PR' => '41', 'PE' => '26', 'PI' => '22', 'RJ' => '33', 'RN' => '24',
            'RS' => '43', 'RO' => '11', 'RR' => '14', 'SC' => '42', 'SP' => '35',
            'SE' => '28', 'TO' => '17'
        ];

        return $codigos[$uf] ?? '35';
    }

    public function autorizarNFe($notaFiscalId)
    {
        $notaFiscal = NotaFiscal::with(['itens.produto', 'emitente', 'destinatario', 'pagamentos'])->findOrFail($notaFiscalId);

        if ($notaFiscal->status !== 'digitacao') {
            throw new \Exception('Nota fiscal já foi processada');
        }

        try {
            // Validar dados antes de autorizar
            $this->validarDadosNFe($notaFiscal);
            
            // Gerar XML se ainda não foi gerado
            if (!$notaFiscal->xml_gerado) {
                $xml = $this->gerarXML($notaFiscal);
                $notaFiscal->update(['xml_gerado' => $xml]);
                $notaFiscal->refresh();
            }
            
            // Simular processo de autorização
            // Em produção, aqui seria:
            // 1. Assinar XML com certificado digital
            // 2. Enviar para SEFAZ via webservice
            // 3. Processar retorno e salvar protocolo
            
            $notaFiscal->update([
                'status' => 'pronto_envio',
                'xml_assinado' => $notaFiscal->xml_gerado, // Simulado - em produção seria o XML assinado
                'protocolo_autorizacao' => null // Será preenchido após envio real à SEFAZ
            ]);

            return $notaFiscal;
        } catch (\Exception $e) {
            Log::error('Erro ao autorizar NFe: ' . $e->getMessage());
            throw $e;
        }
    }
    
    private function validarDadosNFe($notaFiscal)
    {
        $erros = [];
        
        // Validar emitente
        if (!$notaFiscal->emitente) {
            $erros[] = 'Emitente não encontrado';
        } else {
            $emitente = $notaFiscal->emitente;
            if (empty($emitente->cnpj)) {
                $erros[] = 'CNPJ do emitente é obrigatório';
            }
            if (empty($emitente->razao_social)) {
                $erros[] = 'Razão social do emitente é obrigatória';
            }
            if (empty($emitente->inscricao_estadual)) {
                $erros[] = 'Inscrição estadual do emitente é obrigatória';
            }
            if (empty($emitente->logradouro) || empty($emitente->numero) || empty($emitente->bairro) || empty($emitente->municipio) || empty($emitente->uf)) {
                $erros[] = 'Endereço completo do emitente é obrigatório';
            }
            if (empty($emitente->codigo_municipio)) {
                $erros[] = 'Código do município (IBGE) do emitente é obrigatório';
            }
        }
        
        // Validar destinatário
        if (!$notaFiscal->destinatario) {
            $erros[] = 'Destinatário não encontrado';
        } else {
            $destinatario = $notaFiscal->destinatario;
            if (empty($destinatario->cpf_cnpj)) {
                $erros[] = 'CPF/CNPJ do destinatário é obrigatório';
            }
            if (empty($destinatario->nome)) {
                $erros[] = 'Nome do destinatário é obrigatório';
            }
        }
        
        // Validar itens
        if ($notaFiscal->itens->isEmpty()) {
            $erros[] = 'Nota fiscal deve ter pelo menos um item';
        } else {
            foreach ($notaFiscal->itens as $item) {
                if (empty($item->ncm)) {
                    $erros[] = 'NCM é obrigatório para o item: ' . $item->descricao;
                }
                if (empty($item->cfop)) {
                    $erros[] = 'CFOP é obrigatório para o item: ' . $item->descricao;
                }
                if ($item->quantidade <= 0) {
                    $erros[] = 'Quantidade deve ser maior que zero para o item: ' . $item->descricao;
                }
                if ($item->valor_unitario <= 0) {
                    $erros[] = 'Valor unitário deve ser maior que zero para o item: ' . $item->descricao;
                }
            }
        }
        
        // Validar totais
        $somaItens = $notaFiscal->itens->sum('valor_total');
        $tolerancia = 0.01; // Tolerância de 1 centavo
        if (abs($somaItens - $notaFiscal->valor_total_produtos) > $tolerancia) {
            $erros[] = 'Soma dos itens (' . number_format($somaItens, 2, ',', '.') . ') não confere com valor total de produtos (' . number_format($notaFiscal->valor_total_produtos, 2, ',', '.') . ')';
        }
        
        if (!empty($erros)) {
            throw new \Exception('Erros de validação: ' . implode('; ', $erros));
        }
    }

    public function gerarXML($notaFiscal)
    {
        $emitente = $notaFiscal->emitente;
        $destinatario = $notaFiscal->destinatario;
        $itens = $notaFiscal->itens;
        $pagamentos = $notaFiscal->pagamentos;
        
        // Limpar CNPJ/CPF
        $cnpjEmitente = preg_replace('/[^0-9]/', '', $emitente->cnpj);
        $cpfCnpjDest = preg_replace('/[^0-9]/', '', $destinatario->cpf_cnpj ?? '');
        $isCnpjDest = strlen($cpfCnpjDest) == 14;
        
        // Formatar datas
        $dataEmissao = $notaFiscal->data_emissao->format('Y-m-d\TH:i:sP');
        $dataSaida = $notaFiscal->data_saida_entrada ? $notaFiscal->data_saida_entrada->format('Y-m-d\TH:i:sP') : $dataEmissao;
        
        // Código do município do emitente
        $codigoMunicipioEmitente = $emitente->codigo_municipio ?? 0;
        
        // Código do município do destinatário
        $codigoMunicipioDest = $destinatario->codigo_municipio_ibge ?? 0;
        
        // Indicador IE do destinatário
        $indicadorIEDest = $destinatario->indicador_ie ?? '9';
        
        // Inscrição estadual do destinatário
        $ieDest = $destinatario->inscricao_estadual ?? '';
        if ($indicadorIEDest == '9') {
            $ieDest = 'ISENTO';
        }
        
        // Construir XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<NFe xmlns="http://www.portalfiscal.inf.br/nfe">' . "\n";
        $xml .= '  <infNFe Id="NFe' . $notaFiscal->chave_acesso . '" versao="' . $notaFiscal->versao_leiaute . '">' . "\n";
        
        // IDE - Identificação da NF-e
        $xml .= '    <ide>' . "\n";
        $xml .= '      <cUF>' . $this->getCodigoUF($emitente->uf) . '</cUF>' . "\n";
        $xml .= '      <cNF>' . substr($notaFiscal->chave_acesso, -8, 8) . '</cNF>' . "\n";
        $xml .= '      <natOp>' . htmlspecialchars($notaFiscal->natureza_operacao, ENT_XML1, 'UTF-8') . '</natOp>' . "\n";
        $xml .= '      <mod>' . $notaFiscal->modelo . '</mod>' . "\n";
        $xml .= '      <serie>' . $notaFiscal->serie . '</serie>' . "\n";
        $xml .= '      <nNF>' . $notaFiscal->numero . '</nNF>' . "\n";
        $xml .= '      <dhEmi>' . $dataEmissao . '</dhEmi>' . "\n";
        $xml .= '      <dhSaiEnt>' . $dataSaida . '</dhSaiEnt>' . "\n";
        $xml .= '      <tpNF>' . $notaFiscal->tipo_operacao . '</tpNF>' . "\n";
        $xml .= '      <idDest>1</idDest>' . "\n";
        $xml .= '      <cMunFG>' . $codigoMunicipioEmitente . '</cMunFG>' . "\n";
        $xml .= '      <tpImp>1</tpImp>' . "\n";
        $xml .= '      <tpEmis>' . $notaFiscal->tipo_emissao . '</tpEmis>' . "\n";
        $xml .= '      <cDV>' . substr($notaFiscal->chave_acesso, -1) . '</cDV>' . "\n";
        $xml .= '      <tpAmb>' . $notaFiscal->ambiente . '</tpAmb>' . "\n";
        $xml .= '      <finNFe>' . $notaFiscal->finalidade . '</finNFe>' . "\n";
        $xml .= '      <indFinal>0</indFinal>' . "\n";
        $xml .= '      <indPres>1</indPres>' . "\n";
        $xml .= '      <procEmi>0</procEmi>' . "\n";
        $xml .= '      <verProc>' . htmlspecialchars('Sistema Açougue 1.0', ENT_XML1, 'UTF-8') . '</verProc>' . "\n";
        $xml .= '    </ide>' . "\n";
        
        // EMIT - Emitente
        $xml .= '    <emit>' . "\n";
        $xml .= '      <CNPJ>' . $cnpjEmitente . '</CNPJ>' . "\n";
        $xml .= '      <xNome>' . htmlspecialchars($emitente->razao_social, ENT_XML1, 'UTF-8') . '</xNome>' . "\n";
        $xml .= '      <xFant>' . htmlspecialchars($emitente->nome_fantasia ?? $emitente->razao_social, ENT_XML1, 'UTF-8') . '</xFant>' . "\n";
        $xml .= '      <enderEmit>' . "\n";
        $xml .= '        <xLgr>' . htmlspecialchars($emitente->logradouro, ENT_XML1, 'UTF-8') . '</xLgr>' . "\n";
        $xml .= '        <nro>' . htmlspecialchars($emitente->numero, ENT_XML1, 'UTF-8') . '</nro>' . "\n";
        if ($emitente->complemento) {
            $xml .= '        <xCpl>' . htmlspecialchars($emitente->complemento, ENT_XML1, 'UTF-8') . '</xCpl>' . "\n";
        }
        $xml .= '        <xBairro>' . htmlspecialchars($emitente->bairro, ENT_XML1, 'UTF-8') . '</xBairro>' . "\n";
        $xml .= '        <cMun>' . $codigoMunicipioEmitente . '</cMun>' . "\n";
        $xml .= '        <xMun>' . htmlspecialchars($emitente->municipio, ENT_XML1, 'UTF-8') . '</xMun>' . "\n";
        $xml .= '        <UF>' . $emitente->uf . '</UF>' . "\n";
        $xml .= '        <CEP>' . preg_replace('/[^0-9]/', '', $emitente->cep) . '</CEP>' . "\n";
        if ($emitente->telefone) {
            $xml .= '        <cPais>105</cPais>' . "\n";
            $xml .= '        <xPais>BRASIL</xPais>' . "\n";
            $xml .= '        <fone>' . preg_replace('/[^0-9]/', '', $emitente->telefone) . '</fone>' . "\n";
        } else {
            $xml .= '        <cPais>105</cPais>' . "\n";
            $xml .= '        <xPais>BRASIL</xPais>' . "\n";
        }
        $xml .= '      </enderEmit>' . "\n";
        $xml .= '      <IE>' . preg_replace('/[^0-9]/', '', $emitente->inscricao_estadual ?? '') . '</IE>' . "\n";
        if ($emitente->inscricao_municipal) {
            $xml .= '      <IM>' . htmlspecialchars($emitente->inscricao_municipal, ENT_XML1, 'UTF-8') . '</IM>' . "\n";
        }
        $xml .= '      <CRT>' . $emitente->crt . '</CRT>' . "\n";
        $xml .= '    </emit>' . "\n";
        
        // DEST - Destinatário
        $xml .= '    <dest>' . "\n";
        if ($isCnpjDest) {
            $xml .= '      <CNPJ>' . $cpfCnpjDest . '</CNPJ>' . "\n";
        } else {
            $xml .= '      <CPF>' . $cpfCnpjDest . '</CPF>' . "\n";
        }
        $xml .= '      <xNome>' . htmlspecialchars($destinatario->nome, ENT_XML1, 'UTF-8') . '</xNome>' . "\n";
        $xml .= '      <enderDest>' . "\n";
        if ($destinatario->logradouro) {
            $xml .= '        <xLgr>' . htmlspecialchars($destinatario->logradouro, ENT_XML1, 'UTF-8') . '</xLgr>' . "\n";
            $xml .= '        <nro>' . htmlspecialchars($destinatario->numero ?? 'S/N', ENT_XML1, 'UTF-8') . '</nro>' . "\n";
            if ($destinatario->complemento) {
                $xml .= '        <xCpl>' . htmlspecialchars($destinatario->complemento, ENT_XML1, 'UTF-8') . '</xCpl>' . "\n";
            }
            $xml .= '        <xBairro>' . htmlspecialchars($destinatario->bairro ?? '', ENT_XML1, 'UTF-8') . '</xBairro>' . "\n";
            $xml .= '        <cMun>' . $codigoMunicipioDest . '</cMun>' . "\n";
            $xml .= '        <xMun>' . htmlspecialchars($destinatario->municipio ?? '', ENT_XML1, 'UTF-8') . '</xMun>' . "\n";
            $xml .= '        <UF>' . ($destinatario->uf ?? '') . '</UF>' . "\n";
            if ($destinatario->cep) {
                $xml .= '        <CEP>' . preg_replace('/[^0-9]/', '', $destinatario->cep) . '</CEP>' . "\n";
            }
            $xml .= '        <cPais>105</cPais>' . "\n";
            $xml .= '        <xPais>BRASIL</xPais>' . "\n";
            if ($destinatario->telefone) {
                $xml .= '        <fone>' . preg_replace('/[^0-9]/', '', $destinatario->telefone) . '</fone>' . "\n";
            }
        } else {
            // Fallback para endereço antigo
            $xml .= '        <xLgr>' . htmlspecialchars($destinatario->endereco ?? 'Não informado', ENT_XML1, 'UTF-8') . '</xLgr>' . "\n";
            $xml .= '        <nro>S/N</nro>' . "\n";
            $xml .= '        <xBairro>Centro</xBairro>' . "\n";
            $xml .= '        <cMun>3550308</cMun>' . "\n";
            $xml .= '        <xMun>São Paulo</xMun>' . "\n";
            $xml .= '        <UF>SP</UF>' . "\n";
            $xml .= '        <CEP>00000000</CEP>' . "\n";
            $xml .= '        <cPais>105</cPais>' . "\n";
            $xml .= '        <xPais>BRASIL</xPais>' . "\n";
        }
        $xml .= '      </enderDest>' . "\n";
        $xml .= '      <indIEDest>' . $indicadorIEDest . '</indIEDest>' . "\n";
        if ($ieDest && $indicadorIEDest != '9') {
            $xml .= '      <IE>' . preg_replace('/[^0-9]/', '', $ieDest) . '</IE>' . "\n";
        }
        $xml .= '    </dest>' . "\n";
        
        // DET - Detalhes dos produtos
        foreach ($itens as $item) {
            $xml .= '    <det nItem="' . $item->ordem . '">' . "\n";
            $xml .= '      <prod>' . "\n";
            $xml .= '        <cProd>' . htmlspecialchars($item->codigo_item, ENT_XML1, 'UTF-8') . '</cProd>' . "\n";
            $xml .= '        <cEAN></cEAN>' . "\n";
            $xml .= '        <xProd>' . htmlspecialchars($item->descricao, ENT_XML1, 'UTF-8') . '</xProd>' . "\n";
            $xml .= '        <NCM>' . $item->ncm . '</NCM>' . "\n";
            if ($item->cest) {
                $xml .= '        <CEST>' . $item->cest . '</CEST>' . "\n";
            }
            $xml .= '        <CFOP>' . $item->cfop . '</CFOP>' . "\n";
            $xml .= '        <uCom>' . htmlspecialchars($item->unidade, ENT_XML1, 'UTF-8') . '</uCom>' . "\n";
            $xml .= '        <qCom>' . number_format($item->quantidade, 4, '.', '') . '</qCom>' . "\n";
            $xml .= '        <vUnCom>' . number_format($item->valor_unitario, 4, '.', '') . '</vUnCom>' . "\n";
            $xml .= '        <vProd>' . number_format($item->valor_total, 2, '.', '') . '</vProd>' . "\n";
            $xml .= '        <cEANTrib></cEANTrib>' . "\n";
            $xml .= '        <uTrib>' . htmlspecialchars($item->unidade, ENT_XML1, 'UTF-8') . '</uTrib>' . "\n";
            $xml .= '        <qTrib>' . number_format($item->quantidade, 4, '.', '') . '</qTrib>' . "\n";
            $xml .= '        <vUnTrib>' . number_format($item->valor_unitario, 4, '.', '') . '</vUnTrib>' . "\n";
            if ($item->desconto_item > 0) {
                $xml .= '        <vFrete>0.00</vFrete>' . "\n";
                $xml .= '        <vSeg>0.00</vSeg>' . "\n";
                $xml .= '        <vDesc>' . number_format($item->desconto_item, 2, '.', '') . '</vDesc>' . "\n";
            }
            $xml .= '        <vOutro>0.00</vOutro>' . "\n";
            $xml .= '        <indTot>1</indTot>' . "\n";
            $xml .= '      </prod>' . "\n";
            
            // Impostos
            $xml .= '      <imposto>' . "\n";
            
            // ICMS
            $xml .= '        <ICMS>' . "\n";
            $xml .= '          <ICMS00>' . "\n";
            $xml .= '            <orig>' . $item->origem . '</orig>' . "\n";
            $xml .= '            <CST>' . str_pad($item->cst_icms, 2, '0', STR_PAD_LEFT) . '</CST>' . "\n";
            $xml .= '            <modBC>0</modBC>' . "\n";
            $xml .= '            <vBC>0.00</vBC>' . "\n";
            $xml .= '            <pICMS>0.00</pICMS>' . "\n";
            $xml .= '            <vICMS>0.00</vICMS>' . "\n";
            $xml .= '          </ICMS00>' . "\n";
            $xml .= '        </ICMS>' . "\n";
            
            // PIS
            $xml .= '        <PIS>' . "\n";
            $xml .= '          <PISAliq>' . "\n";
            $xml .= '            <CST>' . str_pad($item->cst_pis, 2, '0', STR_PAD_LEFT) . '</CST>' . "\n";
            $xml .= '            <vBC>0.00</vBC>' . "\n";
            $xml .= '            <pPIS>0.00</pPIS>' . "\n";
            $xml .= '            <vPIS>0.00</vPIS>' . "\n";
            $xml .= '          </PISAliq>' . "\n";
            $xml .= '        </PIS>' . "\n";
            
            // COFINS
            $xml .= '        <COFINS>' . "\n";
            $xml .= '          <COFINSAliq>' . "\n";
            $xml .= '            <CST>' . str_pad($item->cst_cofins, 2, '0', STR_PAD_LEFT) . '</CST>' . "\n";
            $xml .= '            <vBC>0.00</vBC>' . "\n";
            $xml .= '            <pCOFINS>0.00</pCOFINS>' . "\n";
            $xml .= '            <vCOFINS>0.00</vCOFINS>' . "\n";
            $xml .= '          </COFINSAliq>' . "\n";
            $xml .= '        </COFINS>' . "\n";
            
            $xml .= '      </imposto>' . "\n";
            $xml .= '    </det>' . "\n";
        }
        
        // TOTAL - Totais da NF-e
        $xml .= '    <total>' . "\n";
        $xml .= '      <ICMSTot>' . "\n";
        $xml .= '        <vBC>0.00</vBC>' . "\n";
        $xml .= '        <vICMS>0.00</vICMS>' . "\n";
        $xml .= '        <vICMSDeson>0.00</vICMSDeson>' . "\n";
        $xml .= '        <vFCP>0.00</vFCP>' . "\n";
        $xml .= '        <vBCST>0.00</vBCST>' . "\n";
        $xml .= '        <vST>0.00</vST>' . "\n";
        $xml .= '        <vFCPST>0.00</vFCPST>' . "\n";
        $xml .= '        <vFCPSTRet>0.00</vFCPSTRet>' . "\n";
        $xml .= '        <vProd>' . number_format($notaFiscal->valor_total_produtos, 2, '.', '') . '</vProd>' . "\n";
        $xml .= '        <vFrete>' . number_format($notaFiscal->valor_frete, 2, '.', '') . '</vFrete>' . "\n";
        $xml .= '        <vSeg>' . number_format($notaFiscal->valor_seguro, 2, '.', '') . '</vSeg>' . "\n";
        $xml .= '        <vDesc>' . number_format($notaFiscal->valor_desconto, 2, '.', '') . '</vDesc>' . "\n";
        $xml .= '        <vII>0.00</vII>' . "\n";
        $xml .= '        <vIPI>0.00</vIPI>' . "\n";
        $xml .= '        <vIPIDevol>0.00</vIPIDevol>' . "\n";
        $xml .= '        <vPIS>' . number_format($notaFiscal->valor_impostos, 2, '.', '') . '</vPIS>' . "\n";
        $xml .= '        <vCOFINS>' . number_format($notaFiscal->valor_impostos, 2, '.', '') . '</vCOFINS>' . "\n";
        $xml .= '        <vOutro>0.00</vOutro>' . "\n";
        $xml .= '        <vNF>' . number_format($notaFiscal->valor_total_nfe, 2, '.', '') . '</vNF>' . "\n";
        $xml .= '        <vTotTrib>0.00</vTotTrib>' . "\n";
        $xml .= '      </ICMSTot>' . "\n";
        $xml .= '    </total>' . "\n";
        
        // TRANSP - Transporte (opcional, mas vamos incluir básico)
        $xml .= '    <transp>' . "\n";
        $xml .= '      <modFrete>9</modFrete>' . "\n";
        $xml .= '    </transp>' . "\n";
        
        // PAG - Pagamento
        if (count($pagamentos) > 0) {
            $xml .= '    <pag>' . "\n";
            foreach ($pagamentos as $pagamento) {
                $xml .= '      <detPag>' . "\n";
                $xml .= '        <indPag>0</indPag>' . "\n";
                $formaPagamento = $pagamento->formaPagamento;
                if ($formaPagamento) {
                    $tipoPagamento = $this->getTipoPagamentoNFe($formaPagamento->nome);
                    $xml .= '        <tPag>' . $tipoPagamento . '</tPag>' . "\n";
                } else {
                    $xml .= '        <tPag>01</tPag>' . "\n"; // Dinheiro
                }
                $xml .= '        <vPag>' . number_format($pagamento->valor_pagamento, 2, '.', '') . '</vPag>' . "\n";
                $xml .= '      </detPag>' . "\n";
            }
            $xml .= '    </pag>' . "\n";
        } else {
            $xml .= '    <pag>' . "\n";
            $xml .= '      <detPag>' . "\n";
            $xml .= '        <indPag>0</indPag>' . "\n";
            $xml .= '        <tPag>01</tPag>' . "\n";
            $xml .= '        <vPag>' . number_format($notaFiscal->valor_total_nfe, 2, '.', '') . '</vPag>' . "\n";
            $xml .= '      </detPag>' . "\n";
            $xml .= '    </pag>' . "\n";
        }
        
        // INFADIC - Informações adicionais
        if ($notaFiscal->informacoes_adicionais) {
            $xml .= '    <infAdic>' . "\n";
            $xml .= '      <infAdFisco></infAdFisco>' . "\n";
            $xml .= '      <infCpl>' . htmlspecialchars($notaFiscal->informacoes_adicionais, ENT_XML1, 'UTF-8') . '</infCpl>' . "\n";
            $xml .= '    </infAdic>' . "\n";
        }
        
        $xml .= '  </infNFe>' . "\n";
        $xml .= '</NFe>';
        
        return $xml;
    }
    
    private function getTipoPagamentoNFe($nomeFormaPagamento)
    {
        $nome = strtoupper($nomeFormaPagamento);
        
        if (strpos($nome, 'DINHEIRO') !== false) {
            return '01';
        } elseif (strpos($nome, 'CHEQUE') !== false) {
            return '02';
        } elseif (strpos($nome, 'CARTAO') !== false || strpos($nome, 'CARTÃO') !== false) {
            return '03';
        } elseif (strpos($nome, 'CREDITO') !== false || strpos($nome, 'CRÉDITO') !== false) {
            return '04';
        } elseif (strpos($nome, 'DEBITO') !== false || strpos($nome, 'DÉBITO') !== false) {
            return '05';
        } else {
            return '99'; // Outros
        }
    }
}

