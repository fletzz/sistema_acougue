<?php

namespace App\Http\Controllers;

use App\Services\NFeService;
use App\Models\NotaFiscal;
use App\Models\Venda;
use App\Models\Emitente;
use Illuminate\Http\Request;

class NFeController extends Controller
{
    protected $nfeService;

    public function __construct(NFeService $nfeService)
    {
        $this->nfeService = $nfeService;
    }

    public function index()
    {
        $notasFiscais = NotaFiscal::with(['venda', 'emitente', 'destinatario'])
            ->latest()
            ->get();

        return view('nfe.index', ['notasFiscais' => $notasFiscais]);
    }

    public function create(Request $request, $vendaId = null)
    {
        $vendas = Venda::whereDoesntHave('notaFiscal')->get();
        $emitentes = Emitente::all();

        return view('nfe.create', [
            'vendas' => $vendas,
            'emitentes' => $emitentes,
            'vendaId' => $vendaId ?? $request->input('venda_id')
        ]);
    }

    public function store(Request $request)
    {
        $dadosValidados = $request->validate([
            'venda_id' => 'required|exists:vendas,id',
            'emitente_id' => 'required|exists:emitente,id',
            'ambiente' => 'nullable|in:1,2'
        ]);

        $ambiente = $dadosValidados['ambiente'] ?? '2';

        try {
            $notaFiscal = $this->nfeService->gerarNFe(
                $dadosValidados['venda_id'],
                $dadosValidados['emitente_id'],
                $ambiente
            );

            return redirect()->route('nfe.show', $notaFiscal->id)
                ->with('success', 'NFe gerada com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['erro' => 'Erro ao gerar NFe: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $notaFiscal = NotaFiscal::with([
            'venda.items.produto',
            'emitente',
            'destinatario',
            'itens.produto',
            'pagamentos.formaPagamento'
        ])->findOrFail($id);

        return view('nfe.show', ['notaFiscal' => $notaFiscal]);
    }

    public function autorizar($id)
    {
        try {
            $notaFiscal = $this->nfeService->autorizarNFe($id);

            return redirect()->route('nfe.show', $id)
                ->with('success', 'NFe autorizada com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['erro' => 'Erro ao autorizar NFe: ' . $e->getMessage()]);
        }
    }

    public function downloadXml($id)
    {
        $notaFiscal = NotaFiscal::findOrFail($id);

        if (!$notaFiscal->chave_acesso) {
            return back()->withErrors(['erro' => 'NFe não possui chave de acesso']);
        }

        $xml = $this->nfeService->gerarXML($notaFiscal);

        return response($xml, 200)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="NFe_' . $notaFiscal->chave_acesso . '.xml"');
    }
}

