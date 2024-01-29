<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     */
    public function run(): void
    {
        //creating roles
        $role_admin = Role::create(['name' => 'Administrador']);
        $role_commite = Role::create(['name' => 'Comite técnico']);
        $role_delegate = Role::create(['name' => 'Delegado']);
        $role_assistant = Role::create(['name' => 'Asistente']);
        $role_directive = Role::create(['name' => 'Directiva']);

        //creating permissions for role CRUD
        $permission_create_role = Permission::create(['name' => 'Crear rol']);
        $permission_read_role = Permission::create(['name' => 'Ver rol']);
        $permission_update_role = Permission::create(['name' => 'Actualizar rol']);
        $permission_delete_role = Permission::create(['name' => 'Eliminar rol']);

        //creating permissions for permission CRUD
        $permission_create_permission = Permission::create(['name' => 'Crear permiso']);
        $permission_read_permission = Permission::create(['name' => 'Ver permiso']);
        $permission_update_permission = Permission::create(['name' => 'Actualizar permiso']);
        $permission_delete_permission = Permission::create(['name' => 'Eliminar permiso']);

        //creating permissions for user CRUD
        $permission_create_user = Permission::create(['name' => 'Crear usuario']);
        $permission_read_user = Permission::create(['name' => 'Ver usuario']);
        $permission_update_user = Permission::create(['name' => 'Actualizar usuario']);
        $permission_delete_user = Permission::create(['name' => 'Eliminar usuario']);        

        //creating permissions for club CRUD
        $permission_create_club = Permission::create(['name' => 'Crear club']);
        $permission_read_club = Permission::create(['name' => 'Ver club']);
        $permission_update_club = Permission::create(['name' => 'Actualizar club']);
        $permission_delete_club = Permission::create(['name' => 'Eliminar club']);        

        //creating permissions for category CRUD
        $permission_create_category = Permission::create(['name' => 'Crear categoría']);
        $permission_read_category = Permission::create(['name' => 'Ver categoría']);
        $permission_update_category = Permission::create(['name' => 'Actualizar categoría']);
        $permission_delete_category = Permission::create(['name' => 'Eliminar categoría']);        

        //creating permissions for tournament CRUD
        $permission_create_tournament = Permission::create(['name' => 'Crear torneo']);
        $permission_read_tournament = Permission::create(['name' => 'Ver torneo']);
        $permission_update_tournament = Permission::create(['name' => 'Actualizar torneo']);
        $permission_delete_tournament = Permission::create(['name' => 'Eliminar torneo']);        

        //creating permissions for team CRUD
        $permission_create_team = Permission::create(['name' => 'Crear equipo']);
        $permission_read_team = Permission::create(['name' => 'Ver equipo']);
        $permission_update_team = Permission::create(['name' => 'Actualizar equipo']);
        $permission_delete_team = Permission::create(['name' => 'Eliminar equipo']);  

        //creating permissions for player CRUD
        $permission_create_player = Permission::create(['name' => 'Crear jugador']);
        $permission_read_player = Permission::create(['name' => 'Ver jugador']);
        $permission_update_player = Permission::create(['name' => 'Actualizar jugador']);
        $permission_delete_player = Permission::create(['name' => 'Eliminar jugador']);           

        //creating permissions for game_role CRUD
        $permission_create_game_role = Permission::create(['name' => 'Crear rol de partido']);
        $permission_read_game_role = Permission::create(['name' => 'Ver rol de partido']);
        $permission_update_game_role = Permission::create(['name' => 'Actualizar rol de partido']);
        $permission_delete_game_role = Permission::create(['name' => 'Eliminar rol de partido']);  

        //creating permissions for game_scheduling CRUD
        $permission_create_game_scheduling = Permission::create(['name' => 'Crear programación de partido']);
        $permission_read_game_scheduling = Permission::create(['name' => 'Ver programación de partido']);
        $permission_update_game_scheduling = Permission::create(['name' => 'Actualizar programación de partido']);
        $permission_delete_game_scheduling = Permission::create(['name' => 'Eliminar programación de partido']); 

        //creating permissions for game CRUD
        $permission_create_game = Permission::create(['name' => 'Crear partido']);
        $permission_read_game = Permission::create(['name' => 'Ver partido']);
        $permission_update_game = Permission::create(['name' => 'Actualizar partido']);
        $permission_delete_game = Permission::create(['name' => 'Eliminar partido']);  

        /**
         * Array of permissions_admin
         */
        $permissions_admin = [$permission_create_role, $permission_read_role, $permission_update_role, $permission_delete_role,
        $permission_create_permission, $permission_read_permission, $permission_update_permission, $permission_delete_permission,        
        $permission_create_user, $permission_read_user, $permission_update_user, $permission_delete_user,
        $permission_create_club, $permission_read_club, $permission_update_club, $permission_delete_club,   
        $permission_create_category, $permission_read_category, $permission_update_category, $permission_delete_category,        
        $permission_create_tournament, $permission_read_tournament, $permission_update_tournament, $permission_delete_tournament,     
        $permission_create_team, $permission_read_team, $permission_update_team, $permission_delete_team,
        $permission_create_player, $permission_read_player, $permission_update_player, $permission_delete_player,         
        $permission_create_game_role, $permission_read_game_role,$permission_update_game_role, $permission_delete_game_role, 
        $permission_create_game_scheduling,$permission_read_game_scheduling, $permission_update_game_scheduling, $permission_delete_game_scheduling, 
        $permission_create_game, $permission_read_game, $permission_update_game, $permission_delete_game];

        $permissions_commite = [ $permission_create_club, $permission_read_club, $permission_update_club, $permission_delete_club,   
        $permission_create_category, $permission_read_category, $permission_update_category, $permission_delete_category,        
        $permission_create_tournament, $permission_read_tournament, $permission_update_tournament, $permission_delete_tournament,     
        $permission_create_team, $permission_read_team, $permission_update_team, $permission_delete_team,
        $permission_create_player, $permission_read_player, $permission_update_player, $permission_delete_player,         
        $permission_create_game_role, $permission_read_game_role,$permission_update_game_role, $permission_delete_game_role, 
        $permission_create_game_scheduling,$permission_read_game_scheduling, $permission_update_game_scheduling, $permission_delete_game_scheduling, 
        $permission_create_game, $permission_read_game, $permission_update_game, $permission_delete_game];

        $permissions_delegate = [$permission_create_club, $permission_read_club, $permission_update_club, $permission_delete_club,   
        $permission_read_team, $permission_update_team,
        $permission_create_player, $permission_read_player, $permission_update_player];

        $permissions_assitant = [$permission_create_game_role, $permission_read_game_role,$permission_update_game_role, $permission_delete_game_role, 
        $permission_create_game_scheduling,$permission_read_game_scheduling, $permission_update_game_scheduling, $permission_delete_game_scheduling, 
        $permission_create_game, $permission_read_game, $permission_update_game, $permission_delete_game];
        
        $permissions_directive = [$permission_read_club,
        $permission_read_category,
        $permission_read_tournament,
        $permission_read_team,
        $permission_read_player,
        $permission_read_game_role,
        $permission_read_game_scheduling,
        $permission_read_game];

        $role_admin->syncPermissions($permissions_admin);
        $role_commite->syncPermissions($permissions_commite);
        $role_delegate->syncPermissions($permissions_delegate);
        $role_assistant->syncPermissions($permissions_assitant);
        $role_directive->syncPermissions($permissions_directive);
    }
}
