<?php

namespace Database\Seeders;

use App\Abstracts\Model;
use App\Models\Catalog;
use App\Models\CatalogDetail;
use App\Models\Plan;
use App\Models\PlanElement;
use Illuminate\Database\Seeder;

class Plans extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->ods();
        $this->pdot();

        Model::reguard();
    }

    public function pdot()
    {
        $plan = Plan::create([
            'name' => 'Plan de Desarrollo y Ordenamiento Territorial ',
            'type' => 'PDOT',
            'principles' => [],
            'values' => []
        ]);

        PlanElement::create([
            'code' => 'OE1',
            'name' => 'Conservar, proteger y recuperar ecosistemas',
            'description' => 'Conservar, proteger y recuperar lo ecosistemas frágiles a nivel de la Provincia de Tungurahua.',
            'plan_id' => $plan->id,
        ]);

        PlanElement::create([
            'code' => 'OE2',
            'name' => 'Incrementar el recurso agua',
            'description' => 'Incrementar el recurso agua en cantidad y calidad mediante el manejo apropiado de cuencas hídricas.',
            'plan_id' => $plan->id,
        ]);

        PlanElement::create([
            'code' => 'OE3',
            'name' => 'inclusión y equidad social',
            'description' => 'Promover procesos de cohesión, inclusión y equidad social en la provincia de Tungurahua con enfoque de género, generacional e intercultural',
            'plan_id' => $plan->id,
        ]);
    }

    public function ods()
    {
        $plan = Plan::create([
            'name' => 'Objetivos de Desarrollo Sostenible',
            'type' => 'ODS',
            'vision' => 'La Agenda 2030 para el Desarrollo Sostenible, aprobada en septiembre de 2015 por la Asamblea General de las Naciones Unidas, establece una visión transformadora hacia la sostenibilidad económica, social y ambiental de los 193 Estados Miembros que la suscribieron y será la guía de referencia para el trabajo de la institución en pos de esta visión durante los próximos 15 años.',
            'start_year' => 2015,
            'end_year' => 2030,
            'principles' => [],
            'values' => []
        ]);

        PlanElement::create([
            'code' => 1,
            'name' => 'FIN DE LA POBREZA',
            'description' => 'Poner fin a la pobreza en todas sus formas en todo el mundo',
            'plan_id' => $plan->id,
        ]);

        PlanElement::create([
            'code' => 6,
            'name' => 'AGUA LIMPIA Y SANEAMIENTO',
            'description' => 'Garantizar la disponibilidad y la gestión sostenible del agua y el saneamiento para todos.',
            'plan_id' => $plan->id,
        ]);

        PlanElement::create([
            'code' => 8,
            'name' => 'TRABAJO DECENTE Y CRECIMIENTO ECONÓMICO',
            'description' => 'Promover el crecimiento económico sostenido, inclusivo y sostenible, el empleo pleno y productivo y el trabajo decente para todos.',
            'plan_id' => $plan->id,
        ]);

        PlanElement::create([
            'code' => 11,
            'name' => 'CIUDADES Y COMUNIDADES SOSTENIBLES',
            'description' => 'Lograr que las ciudades y los asentamientos humanos sean inclusivos, seguros, resilientes y sostenibles.',
            'plan_id' => $plan->id,
        ]);

        PlanElement::create([
            'code' => 16,
            'name' => 'PAZ, JUSTICIA E INSTITUCIONES SÓLIDAS',
            'description' => 'Promover sociedades pacíficas e inclusivas para el desarrollo sostenible, facilitar el acceso a la justicia para todos y construir a todos los niveles instituciones eficaces e inclusivas que rindan cuentas.',
            'plan_id' => $plan->id,
        ]);
    }
}
