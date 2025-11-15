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
            foreach ($venda->items as $itemVenda) {
                $produto = $itemVenda->produto;
                
                $ncm = $produto->ncm_codigo ?? '02012000';
                $cfop = '5102';
                $origem = $produto->origem_mercadoria ?? '0';
                $cstIcms = '102';
                $aliquotaIcms = 0;
                $valorIcms = 0;
                $cstPis = '01';
                $aliquotaPis = 0;
                $valorPis = 0;
                $cstCofins = '01';
                $aliquotaCofins = 0;
                $valorCofins = 0;

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
                    'valor_total' => $itemVenda->quantidade * $itemVenda->preco_unitario,
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
            $notaFiscal->update(['chave_acesso' => $chaveAcesso]);

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
        $notaFiscal = NotaFiscal::with(['itens', 'emitente', 'destinatario'])->findOrFail($notaFiscalId);

        if ($notaFiscal->status !== 'digitacao') {
            throw new \Exception('Nota fiscal já foi processada');
        }

        try {
            $xml = $this->gerarXML($notaFiscal);
            
            $notaFiscal->update([
                'status' => 'autorizada',
                'protocolo_autorizacao' => 'PROTOCOLO_' . time()
            ]);

            return $notaFiscal;
        } catch (\Exception $e) {
            Log::error('Erro ao autorizar NFe: ' . $e->getMessage());
            throw $e;
        }
    }

    private function gerarXML($notaFiscal)
    {
        return '<?xml version="1.0" encoding="UTF-8"?><nfeProc></nfeProc>';
    }
}

