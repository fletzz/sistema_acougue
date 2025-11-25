<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Criar Roles
        $admin = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrador',
            'description' => 'Acesso total ao sistema'
        ]);

        $gerente = Role::create([
            'name' => 'gerente',
            'display_name' => 'Gerente',
            'description' => 'Acesso a todas as funcionalidades exceto configurações administrativas'
        ]);

        $vendedor = Role::create([
            'name' => 'vendedor',
            'display_name' => 'Vendedor',
            'description' => 'Acesso a vendas e consultas básicas'
        ]);

        $caixa = Role::create([
            'name' => 'caixa',
            'display_name' => 'Caixa',
            'description' => 'Acesso apenas a vendas e caixa'
        ]);

        // Criar Permissões - Vendas
        $permissions = [
            // Vendas
            ['name' => 'vendas.visualizar', 'display_name' => 'Visualizar Vendas', 'group' => 'vendas'],
            ['name' => 'vendas.criar', 'display_name' => 'Criar Vendas', 'group' => 'vendas'],
            ['name' => 'vendas.cancelar', 'display_name' => 'Cancelar Vendas', 'group' => 'vendas'],
            
            // Produtos
            ['name' => 'produtos.visualizar', 'display_name' => 'Visualizar Produtos', 'group' => 'produtos'],
            ['name' => 'produtos.criar', 'display_name' => 'Criar Produtos', 'group' => 'produtos'],
            ['name' => 'produtos.editar', 'display_name' => 'Editar Produtos', 'group' => 'produtos'],
            ['name' => 'produtos.excluir', 'display_name' => 'Excluir Produtos', 'group' => 'produtos'],
            
            // Clientes
            ['name' => 'clientes.visualizar', 'display_name' => 'Visualizar Clientes', 'group' => 'clientes'],
            ['name' => 'clientes.criar', 'display_name' => 'Criar Clientes', 'group' => 'clientes'],
            ['name' => 'clientes.editar', 'display_name' => 'Editar Clientes', 'group' => 'clientes'],
            ['name' => 'clientes.excluir', 'display_name' => 'Excluir Clientes', 'group' => 'clientes'],
            
            // Estoque
            ['name' => 'estoque.visualizar', 'display_name' => 'Visualizar Estoque', 'group' => 'estoque'],
            ['name' => 'estoque.ajustar', 'display_name' => 'Ajustar Estoque', 'group' => 'estoque'],
            ['name' => 'estoque.entrada', 'display_name' => 'Entrada de Mercadoria', 'group' => 'estoque'],
            ['name' => 'estoque.transformacao', 'display_name' => 'Transformação de Produtos', 'group' => 'estoque'],
            
            // Caixa
            ['name' => 'caixa.visualizar', 'display_name' => 'Visualizar Caixa', 'group' => 'caixa'],
            ['name' => 'caixa.abrir', 'display_name' => 'Abrir Caixa', 'group' => 'caixa'],
            ['name' => 'caixa.fechar', 'display_name' => 'Fechar Caixa', 'group' => 'caixa'],
            ['name' => 'caixa.movimentacao', 'display_name' => 'Movimentar Caixa', 'group' => 'caixa'],
            
            // Contas a Receber
            ['name' => 'contas_receber.visualizar', 'display_name' => 'Visualizar Contas a Receber', 'group' => 'financeiro'],
            ['name' => 'contas_receber.receber', 'display_name' => 'Receber Contas', 'group' => 'financeiro'],
            
            // Relatórios
            ['name' => 'relatorios.visualizar', 'display_name' => 'Visualizar Relatórios', 'group' => 'relatorios'],
            ['name' => 'relatorios.vendas', 'display_name' => 'Relatório de Vendas', 'group' => 'relatorios'],
            ['name' => 'relatorios.lucratividade', 'display_name' => 'Relatório de Lucratividade', 'group' => 'relatorios'],
            ['name' => 'relatorios.estoque', 'display_name' => 'Relatório de Estoque', 'group' => 'relatorios'],
            
            // NF-e
            ['name' => 'nfe.visualizar', 'display_name' => 'Visualizar NF-e', 'group' => 'nfe'],
            ['name' => 'nfe.criar', 'display_name' => 'Criar NF-e', 'group' => 'nfe'],
            ['name' => 'nfe.autorizar', 'display_name' => 'Autorizar NF-e', 'group' => 'nfe'],
            
            // Configurações
            ['name' => 'configuracoes.visualizar', 'display_name' => 'Visualizar Configurações', 'group' => 'configuracoes'],
            ['name' => 'configuracoes.editar', 'display_name' => 'Editar Configurações', 'group' => 'configuracoes'],
            ['name' => 'usuarios.gerenciar', 'display_name' => 'Gerenciar Usuários', 'group' => 'configuracoes'],
        ];

        foreach ($permissions as $perm) {
            Permission::create($perm);
        }

        // Atribuir todas as permissões ao Admin
        $admin->permissions()->attach(Permission::all());

        // Permissões do Gerente
        $gerente->givePermission('vendas.visualizar');
        $gerente->givePermission('vendas.criar');
        $gerente->givePermission('vendas.cancelar');
        $gerente->givePermission('produtos.visualizar');
        $gerente->givePermission('produtos.criar');
        $gerente->givePermission('produtos.editar');
        $gerente->givePermission('produtos.excluir');
        $gerente->givePermission('clientes.visualizar');
        $gerente->givePermission('clientes.criar');
        $gerente->givePermission('clientes.editar');
        $gerente->givePermission('clientes.excluir');
        $gerente->givePermission('estoque.visualizar');
        $gerente->givePermission('estoque.ajustar');
        $gerente->givePermission('estoque.entrada');
        $gerente->givePermission('estoque.transformacao');
        $gerente->givePermission('caixa.visualizar');
        $gerente->givePermission('caixa.abrir');
        $gerente->givePermission('caixa.fechar');
        $gerente->givePermission('caixa.movimentacao');
        $gerente->givePermission('contas_receber.visualizar');
        $gerente->givePermission('contas_receber.receber');
        $gerente->givePermission('relatorios.visualizar');
        $gerente->givePermission('relatorios.vendas');
        $gerente->givePermission('relatorios.lucratividade');
        $gerente->givePermission('relatorios.estoque');
        $gerente->givePermission('nfe.visualizar');
        $gerente->givePermission('nfe.criar');
        $gerente->givePermission('nfe.autorizar');

        // Permissões do Vendedor
        $vendedor->givePermission('vendas.visualizar');
        $vendedor->givePermission('vendas.criar');
        $vendedor->givePermission('produtos.visualizar');
        $vendedor->givePermission('clientes.visualizar');
        $vendedor->givePermission('clientes.criar');
        $vendedor->givePermission('clientes.editar');
        $vendedor->givePermission('estoque.visualizar');
        $vendedor->givePermission('contas_receber.visualizar');
        $vendedor->givePermission('relatorios.visualizar');
        $vendedor->givePermission('relatorios.vendas');

        // Permissões do Caixa
        $caixa->givePermission('vendas.visualizar');
        $caixa->givePermission('vendas.criar');
        $caixa->givePermission('produtos.visualizar');
        $caixa->givePermission('clientes.visualizar');
        $caixa->givePermission('caixa.visualizar');
        $caixa->givePermission('caixa.movimentacao');
        $caixa->givePermission('contas_receber.visualizar');
        $caixa->givePermission('contas_receber.receber');
    }
}
