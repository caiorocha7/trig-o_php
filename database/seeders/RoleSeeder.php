<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Criar permissões básicas
        Permission::create(['name' => 'visualizar_produto']);
        Permission::create(['name' => 'adicionar_produto']);
        Permission::create(['name' => 'atualizar_produto']);
        Permission::create(['name' => 'excluir_produto']);
        Permission::create(['name' => 'visualizar_encomenda']);
        Permission::create(['name' => 'visualizar_relatorios']);

        // Criar papéis e associar permissões
        $masterRole = Role::create(['name' => 'Master', 'guard_name' => 'web']);
        $padraoRole = Role::create(['name' => 'Padrão', 'guard_name' => 'web']);

        // Atribuir todas as permissões ao papel Master
        $masterRole->givePermissionTo(Permission::all());

        // Atribuir permissões específicas ao papel Padrão
        $padraoRole->givePermissionTo(['visualizar_produto', 'visualizar_encomenda', 'visualizar_relatorios']);
    }
}
