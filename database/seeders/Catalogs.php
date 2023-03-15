<?php

namespace Database\Seeders;

use App\Abstracts\Model;
use App\Models\Catalog;
use App\Models\CatalogDetail;
use Illuminate\Database\Seeder;

class Catalogs extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->axis();
        $this->focus();
        $this->projectStatus();
        $this->units();
        $this->threshold();

        Model::reguard();
    }

    public function threshold()
    {
        $threshold = Catalog::create([
            'name' => 'threshold',
            'description' => 'Umbrales de indicadores',
            'enabled' => '1',
        ]);

        CatalogDetail::create(
            [
                'catalog_id' => $threshold->id,
                'value' => json_encode([
                    [
                        "type" => "ascending",
                        "status" => "Danger",
                        "min" => "0",
                        "max" => "69.99"
                    ],
                    [
                        "type" => "ascending",
                        "status" => "Warning",
                        "min" => "70",
                        "max" => "84.99"
                    ],
                    [
                        "type" => "ascending",
                        "status" => "Success",
                        "min" => "85",
                        "max" => "99999"
                    ],
                    [
                        "type" => "descending",
                        "status" => "Danger",
                        "min" => "115",
                        "max" => "99999"
                    ],
                    [
                        "type" => "descending",
                        "status" => "Warning",
                        "min" => "100.01",
                        "max" => "114.99"
                    ],
                    [
                        "type" => "descending",
                        "status" => "Success",
                        "min" => "0",
                        "max" => "100"
                    ],
                    [
                        "type" => "tolerance",
                        "status" => "Danger",
                        "min" => "15",
                        "max" => "99999"
                    ],
                    [
                        "type" => "tolerance",
                        "status" => "Warning",
                        "min" => "10.01",
                        "max" => "14.99"
                    ],
                    [
                        "type" => "tolerance",
                        "status" => "Success",
                        "min" => "0",
                        "max" => "10"
                    ]
                ]),
                'enabled' => 1,
            ]
        );
    }

    public function units()
    {
        $unit = Catalog::create([
            'name' => 'measure_units',
            'description' => 'Unidades de medida',
            'enabled' => '1',
        ]);

        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'UNIDAD (U)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'PORCIENTO (%)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'KILOMETRO (Km)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'KILOGRAMO (Kg)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'GRAMO (g)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'METRO (m)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'PULGADA (in)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'GALON (gl)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'LITRO (l)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'TONELADA (t)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'METRO CUBICO (m3)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'PIES (p)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'BARRIL (b)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'CENTIMETRO CUBICO (cm3)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'CENTIMETRO CUADRADO (cm2)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'CENTIGRAMO (cg)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'LIBRA (lb)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'MILIMETRO (mlm)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'YARDA (y)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'MILIGRAMO (mlg)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'PAR (par)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'QUINTAL (q)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'METRO CUADRADO (m2)',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $unit->id,
                'value' => 'KILOMETRO CUADRADO (km2)',
                'enabled' => 1
            ]
        );
    }

    public function axis()
    {
        $axis = Catalog::create([
            'name' => 'axis',
            'description' => 'Ejes estratégicos',
            'enabled' => '1',
        ]);

        CatalogDetail::create(
            [
                'catalog_id' => $axis->id,
                'value' => 'Desarrollo local sostenible',
                'enabled' => 1,
                'icon' => 'users'
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $axis->id,
                'value' => 'Desarrollo institucional',
                'enabled' => 1,
                'icon' => 'university'
            ]
        );
    }

    public function focus()
    {
        $focus = Catalog::create([
            'name' => 'focus',
            'description' => 'Enfoques',
            'enabled' => '1',
        ]);
        CatalogDetail::create(
            [
                'catalog_id' => $focus->id,
                'value' => 'Ciudadanía',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $focus->id,
                'value' => 'Calidad del servicio',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $focus->id,
                'value' => 'Gestión Pública',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $focus->id,
                'value' => 'Institucionalidad',
                'enabled' => 1
            ]
        );
    }

    public function projectStatus()
    {
        $status = Catalog::create([
            'name' => 'project_status',
            'description' => 'Estado de proyectos',
            'enabled' => '1',
        ]);

        CatalogDetail::create(
            [
                'catalog_id' => $status->id,
                'value' => 'EJECUCIÓN',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $status->id,
                'value' => 'CANCELADO',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $status->id,
                'value' => 'CERRADO',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $status->id,
                'value' => 'COMPLETADO',
                'enabled' => 1
            ]
        );
        CatalogDetail::create(
            [
                'catalog_id' => $status->id,
                'value' => 'SUSPENDIDO',
                'enabled' => 1
            ]
        );
    }
}
