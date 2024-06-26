<?php

namespace Database\Seeders;

use App\Http\Integrations\PokemonTcg\PokemonTcg;
use App\Http\Integrations\PokemonTcg\Requests\GetTypesRequest;
use App\Models\Type;
use Illuminate\Database\Seeder;

class TypesSeeder extends Seeder
{
    public function run(): void
    {
        $tcg = new PokemonTcg;
        $tcg->send(new GetTypesRequest)
            ->collect('data')
            ->each(function ($type) {
                if (! Type::where('name', $type)->exists()) {
                    Type::create(['name' => $type]);
                }
            });
    }
}
