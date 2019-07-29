<?php

namespace app\controllers;

use app\components\UBool;
use app\components\ULog;
use app\components\UString;
use app\models\Project;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * ActivityController implements the CRUD actions for Activity model.
 */
class GraphicController extends ControladorController
{
    private $_rubros_db = null;
    private $_rubros_db_cantidad = null;
    private $_rubros_post_cantidad = null;
    private $_paises_db = null;
    private $_paises_db_cantidad = null;
    private $_paises_post_cantidad = null;

    public function actionDashboard()
    {
        return $this->render('dashboard');
    }

    /*INICIO DE DATA PARA DASHBOARD*/
    public function actionProyecto()
    {
        $this->validacionPost();
        Yii::$app->response->format = Response::FORMAT_JSON;
        $idProject = Yii::$app->request->post('proyecto');
        $proyecto = Project::find()->where(['id' => $idProject])->one();
        return ['proyecto' => $proyecto ? $proyecto->ToArrayString(['colores']) : null,];
    }

    public function actionCantidadProyectos()
    {
        $this->validacionPost();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['proyectos' => $this->cantidadProyectos(),];
    }

    private function cantidadProyectos()
    {
        $subquery = (new Query());
        $subquery->select('count(distinct p.id)')
            ->from('event e')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin(['pc' => $this->ProjectProductQuery()], 'pc.project_id = p.id');

        $this->AplicarFiltros($subquery);
        return $subquery->scalar();
    }

    private function AplicarFiltros(&$query)
    {
        /* @var $query Query */

        /*Rango de Fechas*/
        $request = Yii::$app->request;
        $desde = $request->post('desde');
        $hasta = $request->post('hasta');
        if ($desde && $hasta)
            $query->andFilterWhere(['>=', 'e.start', $desde])->andFilterWhere(['<=', 'e.start', $hasta]);

        /*Rubros*/
        if ($this->getRubrosAlgunoSeleccionado())
            $query->andFilterWhere(['in', 'pc.product', $this->getRubros()]);
        if ($this->getRubrosNingunoSeleccionado())
            $query->andWhere('pc.product is null');

        /*Proyecto*/
        $proyecto = $request->post('proyecto');
        if ($proyecto)
            $query->andFilterWhere(['p.id' => $proyecto]);

        /*Paises*/
        if ($this->getPaisesAlgunoSeleccionado())
            $query->andFilterWhere(['in', 'e.country_id', $this->getPaises()]);
        if ($this->getPaisesNingunoSeleccionado())
            $query->andWhere('e.country_id is null');

    }

    private function getRubrosAlgunoSeleccionado()
    {
        return !$this->getRubrosTodosSeleccionados() && $this->getRubrosPostCantidad() > 0 && !$this->getRubrosNingunoSeleccionado();
    }

    private function getRubrosTodosSeleccionados()
    {
        return UBool::Str2Bool(Yii::$app->request->post('rubros_todos'));
    }

    private function getRubrosPostCantidad()
    {
        $request = Yii::$app->request;
        $this->_rubros_post_cantidad = $request->post('rubros', []);
        return count($this->_rubros_post_cantidad);
    }

    private function getRubrosNingunoSeleccionado()
    {
        $request = Yii::$app->request;
        $rubrosPost = $request->post('rubros');
        if (!$rubrosPost && $this->getExistePost())
            return true;
        if ($rubrosPost && $this->getExistePost()) {
            if ($this->getRubrosPostCantidad() == 0)
                return true;
            if ($_POST['rubros'][0] == '')
                return true;
        }
        return false;

    }

    private function getExistePost()
    {
        return Yii::$app->request->post('post', 'false') != 'false';
    }

    private function getRubros($value = '')
    {
        $request = Yii::$app->request;
        $rubros = $request->post('rubros', []);
        foreach ($rubros as $k => $p) {
            if (empty($p) || $p == '')
                $rubros[$k] = $value;
        }
        return $rubros;
    }

    private function getPaisesAlgunoSeleccionado()
    {
        return !$this->getPaisesTodosSeleccionados() && $this->getPaisesPostCantidad() > 0 && !$this->getPaisesNingunoSeleccionado();
    }

    private function getPaisesTodosSeleccionados()
    {
        return UBool::Str2Bool(Yii::$app->request->post('paises_todos'));
    }

    private function getPaisesPostCantidad()
    {
        $request = Yii::$app->request;
        $this->_paises_post_cantidad = $request->post('paises', []);
        return count($this->_paises_post_cantidad);
    }

    private function getPaisesNingunoSeleccionado()
    {
        $request = Yii::$app->request;
        $paisesPost = $request->post('paises');

        if (!$paisesPost && $this->getExistePost())
            return true;
        if ($paisesPost && $this->getExistePost()) {

            if ($this->getPaisesPostCantidad() == 0)
                return true;
            if ($_POST['paises'][0] == '')
                return true;
        }
        return false;

    }

    private function getPaises($value = null)
    {
        $request = Yii::$app->request;
        $paises = $request->post('paises', []);

        foreach ($paises as $k => $p) {
            if (empty($p) || $p == '')
                $paises[$k] = $value;
        }
        return $paises;
    }

    public function actionCantidadEventos()
    {
        $this->validacionPost();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['cantidadEventos' => $this->cantidadEventos()];
    }

    private function cantidadEventos()
    {


        $subquery = (new Query());
        $subquery
            ->select([
                'eventos' => 'count(distinct e.id)',
                'actividades' => 'count(distinct e.structure_id)'
            ])
            ->from('event e')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin(['pc' => $this->ProjectProductQuery()], 'pc.project_id = p.id');
        $this->AplicarFiltros($subquery);
        return $subquery->one();

    }

    public function actionGraficoActividades()
    {
        $this->validacionPost();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['actividades' => $this->ParticipantesActividadPorSexo(),];
    }

    private function ParticipantesActividadPorSexo()
    {
        $subquery = (new Query());
        $subquery->select([
            'e.structure_id as activity_id',
            "case when p.id >0 then concat(act.description,  ' / ', p.name) else act.description end as name",
            'c.id',
            'c.sex'
        ])->from('attendance a')
            ->leftJoin('contact c', 'a.contact_id = c.id')
            ->leftJoin('event e', 'a.event_id = e.id')
            ->leftJoin('country pa', 'e.country_id= pa.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin(['pc' => $this->ProjectProductQuery()], 'pc.project_id = p.id');

        $this->AplicarFiltros($subquery);

        $subquery->groupBy(['act.id', 'c.id', 'e.structure_id', 'p.id', 'act.description', 'p.name']);

        $query = (new Query());
        $query
            ->select([
                'activity_id',
                'name',
                "COUNT(case when sex = 'F' then 1 else NULL end) AS f",
                "COUNT(case when sex = 'M' then 1 else NULL end) AS m",
                "count(sex) as total",
            ])
            ->from(['q' => $subquery])
            ->groupBy(['activity_id', 'name'])
            ->orderBy(['total' => SORT_DESC]);
        return $query->all();
    }

    public function actionPaises()
    {
        $this->validacionPost();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['paises' => $this->Paises(), 'todos' => (bool)$this->getPaisesTodosSeleccionados(), 'ninguno' => (bool)$this->getPaisesNingunoSeleccionado()];
    }

    private function Paises()
    {
        $paises = $this->getPaises(0);
        $estado = $this->getPaisesTodosSeleccionados();
        $result = $this->getPaisesDb();

        foreach ($result as $key => $value) $result[$key]['active'] = $estado;

        if ($paises) {
            $data = [];
            foreach ($result as $value) {
                $value['active'] = $estado;
                $data[(int)$value['id']] = $value;
            }
            if (!$estado)
                foreach ($paises as $p)
                    if (isset($data[$p]['country_id']))
                        $data[(int)$p]['active'] = (bool)true;
            return array_values($data);
        }
        return $result;
    }

    private function getPaisesDb()
    {

        if (!$this->_paises_db) {
            $request = Yii::$app->request;
            $subquery = (new Query());
            $subquery->select([
                "COALESCE(t.value, 'N/E') as country, COALESCE(t.id,0) as id, 'true' as active",
            ])->from('event e')
                ->leftJoin('country t', 'e.country_id = t.id')
                ->leftJoin('structure act', 'e.structure_id = act.id')
                ->leftJoin('project p', 'act.project_id = p.id')
                ->where('t.value is not null')
                ->groupBy(["COALESCE(t.value, 'N/E')", 't.id']);
            $desde = $request->post('desde');
            $hasta = $request->post('hasta');
            if ($desde && $hasta)
                $subquery->andFilterWhere(['>=', 'e.start', $desde])->andFilterWhere(['<=', 'e.start', $hasta]);
            $proyecto = $request->post('proyecto');
            if ($proyecto)
                $subquery->andFilterWhere(['p.id' => $proyecto]);


            $this->_paises_db = $subquery->all();
        }
        return $this->_paises_db;
    }

    public function actionRubros()
    {
        $this->validacionPost();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['rubros' => $this->Rubros(),];
    }

    private function Rubros()
    {
        $result = $this->getRubrosDB();
        $rubros = $this->getRubros(0);
        $estado = $this->getRubrosTodosSeleccionados();
        foreach ($result as $key => $value)
            $result[$key]['active'] = (bool)$estado;

        if ($rubros) {
            $data = [];
            foreach ($result as $value) {
                $value['active'] = $estado;
                $data[$value['id']] = $value;
            }
            if (!$estado)
                foreach ($rubros as $p)
                    if (isset($data[$p]['rubro']))
                        $data[$p]['active'] = (bool)true;

            return array_values($data);
        }
        return $result;
    }

    private function getRubrosDB()
    {
        if ($this->_rubros_db === null) {
            $request = Yii::$app->request;
            $subquery = (new Query());
            $subquery->select([
                "COALESCE(UPPER(product), 'N/E') as rubro, COALESCE(UPPER(product),'0') as id, 'true' as active",
            ])->from('project_contact')
                ->where('product is not null')
                ->groupBy(["COALESCE(UPPER(product), 'N/E')", 'UPPER(product)']);
            $proyecto = $request->post('proyecto');
            if ($proyecto)
                $subquery->andFilterWhere(['project_id' => $proyecto]);
            $this->_rubros_db = $subquery->all();
        }
        return $this->_rubros_db;
    }

    public function actionGraficoOrganizaciones()
    {
        $this->validacionPost();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return (['organizaciones' => $this->OrganizacionesTipoFormato()]);
    }

    private function OrganizacionesTipoFormato()
    {
        $org = $this->Organizaciones();
        foreach ($org as $key => $v) $org[$key]['value'] = 1;
        $result = $this->OrganizacionesTipo();
        $data = [];
        $colorNumero = 0;
        $colores = ['#B2BB1E', '#00AAA7', '#472A2B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'];
        foreach ($result as $key => $v) {
            $v['color'] = $colores[$colorNumero];
            $colorNumero += 1;
            if ($colorNumero > 9) $colorNumero = 0;
            $v['value'] = 0;
            $data[$v['id']] = $v;
        }
        foreach ($org as $v) $data[$v['parent']]['value'] += 1;

        return ['data' => array_merge(array_values($data), array_values($org)), 'total' => count($org), 'tipos' => $data, 'total_categorias' => count($data)];
    }

    private function Organizaciones()
    {
        $subquery = (new Query());
        $subquery
            ->select(["distinct o.id, COALESCE(o.name,'NE') as name, COALESCE(cast( t.id as varchar),'ne') parent",])
            ->from('event e')
            ->leftJoin('organization o', 'e.implementing_organization_id = o.id')
            ->leftJoin('country pa', 'e.country_id= pa.id')
            ->leftJoin('organization_type t', 'o.organization_type_id = t.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin(['pc' => $this->ProjectProductQuery()], 'pc.project_id = p.id');
        $this->AplicarFiltros($subquery);
        $subquery->andWhere('o.organization_id is null');
        return $subquery->all();
    }

    private function ProjectProductQuery()
    {
        $subquery = (new Query());
        $subquery->select(['project_id' => 'p.id', 'product' => 'pc.product'])
            ->from(['p' => 'project'])
            ->leftJoin(['pc' => 'project_contact'], 'pc.project_id = p.id')
            ->groupBy(['p.id', 'pc.product']);
        return $subquery;
    }

    private function OrganizacionesTipo()
    {
        $subquery = (new Query());
        $subquery
            ->select(["distinct t.id as id, COALESCE( t.name,'Sin Tipo') as name"])
            ->from('event e')
            ->leftJoin('organization o', 'e.implementing_organization_id = o.id')
            ->leftJoin('country pa', 'e.country_id= pa.id')
            ->leftJoin('organization_type t', 'o.organization_type_id = t.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin(['pc' => $this->ProjectProductQuery()], 'pc.project_id = p.id');
        $this->AplicarFiltros($subquery);
        $subquery->andWhere('o.organization_id is null');

        $query = (new Query());
        $query->select(["COALESCE(cast( id as varchar),'ne') as id, name "])->from(['q' => $subquery]);
        return $query->all();
    }

    /*FIN DE DATA PARA DASHBOARD*/


    /*INICIO DE FUNCIONES AUXILIARES PARA DATA PARA DASHBOARD*/

    public function actionProyectosMetas()
    {
        $this->validacionPost();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['proyectos_metas' => $this->metasProyectos(),];
    }

    private function metasProyectos()
    {
        $request = Yii::$app->request;
        $proyecto = $request->post('proyecto');
        if (is_array($proyecto))
            if (count($proyecto) > 3) return [];
        if (!$proyecto) return [];
        $queryMetas = (new Query());
        $queryMetas
            ->select(['id', 'name', 'goal_men', 'goal_women'])
            ->from('project');
        if ($proyecto)
            $queryMetas->andFilterWhere(['id' => $proyecto]);
        $proyectos = $queryMetas->all();
        $result = [];
        $categorias = [];
        $serieMetaH = [
            'name' => 'Meta Hombres',
            'color' => 'rgba(42,123,153,.9)',
            'data' => [],
            'pointPadding' => 0.3,
            'pointPlacement' => -0.2
        ];
        $serieH = [
            'name' => 'Cantidad Hombres',
            'color' => 'rgba(255,205,85,.8)',
            'data' => [],
            'pointPadding' => 0.4,
            'pointPlacement' => -0.2
        ];
        $serieMetaF = [
            'name' => 'Meta Mujeres',
            'color' => 'rgba(68,87,113,1)',
            'data' => [],
            'pointPadding' => 0.3,
            'pointPlacement' => 0.2
        ];
        $serieF = [
            'name' => 'Cantidad Mujeres',
            'color' => 'rgba(252,110,81,.8)',
            'data' => [],
            'pointPadding' => 0.4,
            'pointPlacement' => 0.2
        ];
        foreach ($proyectos as $p) {
            $categorias[] = $p['name'];
            $serieMetaF['data'][] = (int)$p['goal_women'];
            $serieMetaH['data'][] = (int)$p['goal_men'];
            $subquery = (new Query());
            $subquery
                ->select(['sex'])
                ->from(['sq'=>$this->ConcactQuery()]);

            $queryTotal = (new Query());
            $queryTotal
                ->select([
                    "COUNT(case when sex = 'F' then 1 else NULL end) AS f",
                    "COUNT(case when sex = 'M' then 1 else NULL end) AS m",
                    "count(sex) as total",
                ])
                ->from(['q' => $subquery]);
            $p['meta_total'] = (int)$p['goal_men'] + (int)$p['goal_women'];
            $totales = $queryTotal->one();
            $serieF['data'][] = (int)$totales['f'];
            $serieH['data'][] = (int)$totales['m'];
            $result[] = ArrayHelper::merge($p, $totales);
        }
        $series = [$serieMetaH, $serieH, $serieMetaF, $serieF];
        return ['categorias' => $categorias, 'series' => $series, 'data' => $result];
    }

    public function actionGraficoPaisEventos()
    {
        $this->validacionPost();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->ParticipantesPaisEventosData();
    }

    private function ParticipantesPaisEventosData()
    {
        $result = [];
        $request = Yii::$app->request;
        $subquery = (new Query());
        $subquery->select([
            "COALESCE(ca.name_es,'N/E') as country",
            'ca.*',
            'e.id as eventos',
            "c.sex",
        ])->from('attendance a')
            ->leftJoin('contact c', 'a.contact_id = c.id')
            ->leftJoin('event e', 'a.event_id = e.id')
            ->leftJoin('counrty pa', 'e.country_id= pa.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin(['pc' => $this->ProjectProductQuery()], 'pc.project_id = p.id')
            ->leftJoin('country ca', 'pa.value = ca.id');

        $this->AplicarFiltros($subquery);

        $subquery->groupBy(['c.id', 'e.id', 'ca.id']);
        $query = (new Query());
        $query
            ->select([
                'name',
                "COUNT(sex) as total",
                "COUNT(case when sex = 'F' then 1 else NULL end) AS f",
                "COUNT(case when sex = 'M' then 1 else NULL end) AS m",
                'name_es',
                'x',
                'y',
                'id',
                'count(distinct(eventos)) as eventos'
            ])
            ->from(['q' => $subquery])
            ->groupBy(['name', 'name_es', 'x', 'y', 'id'])
            ->orderBy(['name' => SORT_ASC]);
        $paises = $query->all();
        $paisesArray = [];
        foreach ($paises as $p)
            if (!empty($p['id']))
                $paisesArray[] = [
                    $p['name'],
                    (int)$p['total'],
                    (int)$p['f'],
                    (int)$p['m'],
                    (double)$p['x'],
                    (double)$p['y'],
                    $p['name_es'],
                    UString::lowerCase($p['id']),
                    (int)$p['eventos']
                ];

        $result['paisArray'] = $paisesArray;
        $result['pais'] = $paises;
        return $result;
    }

    public function actionGraficoNacionalidad()
    {
        $this->validacionPost();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->ParticipantesNacionalidadData();
    }

    private function ParticipantesNacionalidadData()
    {
        $result = [];
        $subquery = (new Query());
        $subquery->select([
            "COALESCE(ca.name_es,'N/E') as country",
            'ca.*',
            "c.sex",
        ])->from('attendance a')
            ->leftJoin('contact c', 'a.contact_id = c.id')
            ->leftJoin('event e', 'a.event_id = e.id')
            ->leftJoin('country pa', 'e.country_id= pa.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin(['pc' => $this->ProjectProductQuery()], 'pc.project_id = p.id')
            ->leftJoin('country ca', 'c.country_id = ca.id');

        $this->AplicarFiltros($subquery);

        $subquery->groupBy(["c.id", 'ca.id']);
        $query = (new Query());
        $query
            ->select([
                'name',
                "count(sex) as total",
                "COUNT(case when sex = 'F' then 1 else NULL end) AS f",
                "COUNT(case when sex = 'M' then 1 else NULL end) AS m",
                'name_es',
                'x',
                'y',
                'country',
            ])
            ->from(['q' => $subquery])
            ->groupBy(['country', 'name_es', 'name', 'x', 'y'])
            ->orderBy(['country' => SORT_ASC]);
        $paises = $query->all();
        $paisesArray = [];
        foreach ($paises as $p)
            if (!empty($p['country']))
                $paisesArray[] = [
                    $p['name'],
                    (int)$p['total'],
                    (int)$p['f'],
                    (int)$p['m'],
                    (double)$p['x'],
                    (double)$p['y'],
                    $p['name_es'],
                    UString::lowerCase($p['country'])
                ];

        $result['paisArray'] = $paisesArray;
        $result['pais'] = $paises;
        return $result;
    }

    private function ConcactQuery()
    {
        $query = (new Query());
        $query->select('c.id, min(e.start) as start, c.sex, c.birthdate, education_id')->from('attendance a')
            ->leftJoin('contact c', 'a.contact_id = c.id')
            ->leftJoin('event e', 'a.event_id = e.id')
            ->leftJoin('country pa', 'e.country_id= pa.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin(['pc' => $this->ProjectProductQuery()], 'pc.project_id = p.id')
            ->groupBy('c.id');
        $this->AplicarFiltros($query);
        return $query;
    }

    public function actionGraficoAnioFiscal()
    {
        $this->validacionPost();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->ParticipantesFiscalData();
    }


    private function ParticipantesFiscalData()
    {
        $result = [];
        $request = Yii::$app->request;
        $mes = (int)$request->post('mes_fiscal', 10);


        $subquery = (new Query());
        /*
          Gráfico de año fiscal de modo que
            Si el mes de inicio es del primer semestre se conserva el año,
                    siempre y cuando el mes de inicio sea menor que el mes a validar
            Si el mes de inicio es del segundo semestre se moverá al año siguiente,
                    siempre y cuando el mes de inicio sea mayor que el mes que se está validando
        */

        $subquery->select([

            'id',
            "CASE WHEN $mes<=6 THEN" .
            "	CASE WHEN $mes<= date_part( 'month', start) THEN" .
            "		date_part( 'year',start) " .
            "   ELSE" .
            "		date_part( 'year',start)-1 " .
            "   END " .
            "ELSE" .
            "	CASE WHEN $mes > date_part( 'month', start) THEN" .
            "		date_part( 'year',start) " .
            "   ELSE" .
            "		date_part( 'year',start)+1 " .
            "   END " .
            "END  as type",
            "sex",
        ])->from(['sq' => $this->ConcactQuery()]);


        $query = (new Query());
        $query
            ->select([
                'type',
                "COUNT(case when sex = 'F' then 1 else NULL end) AS f",
                "COUNT(case when sex = 'M' then 1 else NULL end) AS m",
                "count(sex) as total",
            ])
            ->from(['q' => $subquery])
            ->groupBy(['type'])
            ->orderBy(['type' => SORT_ASC]);
        $result['fiscal'] = $query->all();
        return $result;
    }

    public function actionGraficoEdad()
    {
        $this->validacionPost();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->ParticipantesEdadData();
    }

    private function ParticipantesEdadData()
    {
        $result = [];
        $subquery = (new Query());
        $subquery->select([
            "COALESCE( f.name, 'N/E' ) as type",
            "sex",
        ])->from(['sq' => $this->ConcactQuery()])
            ->leftJoin('filter f', "f.slug = 'age' and f.filter_id is not null and date_part('YEAR', age(birthdate)) BETWEEN cast( f.start as INTEGER) and CAST( f.end as INTEGER)");


        $query = (new Query());
        $query
            ->select([
                'type',
                "COUNT(case when sex = 'F' then 1 else NULL end) AS f",
                "COUNT(case when sex = 'M' then 1 else NULL end) AS m",
                "count(sex) as total",
            ])
            ->from(['q' => $subquery])
            ->groupBy(['type'])
            ->orderBy(['type' => SORT_ASC]);
        $result['edad'] = $query->all();
        return $result;
    }

    public function actionGraficoEducacion()
    {
        $this->validacionPost();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->ParticipantesEducacionData();
    }

    private function ParticipantesEducacionData()
    {
        $result = [];

        $subquery = (new Query());
        $subquery->select([
            "COALESCE( edu.name, 'N/E' ) as type",
            "sex",
        ])->from(['sq' => $this->ConcactQuery()])
            ->leftJoin('data_list edu', 'sq.education_id = edu.id');


        $query = (new Query());
        $query
            ->select([
                'type',
                "COUNT(case when sex = 'F' then 1 else NULL end) AS f",
                "COUNT(case when sex = 'M' then 1 else NULL end) AS m",
                "count(sex) as total",
            ])
            ->from(['q' => $subquery])
            ->groupBy(['type'])
            ->orderBy(['type' => SORT_ASC]);
        $result['educacion'] = $query->all();
        return $result;
    }

    public function actionGraficoEventos()
    {
        $this->validacionPost();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['eventos' => $this->ParticipantesEventosPorSexo()];
    }

    private function ParticipantesEventosPorSexo()
    {
        $subquery = (new Query());
        $subquery->select([
            'e.id',
            'e.name',
            'c.id AS contact_id',
            'c.sex',
            'e.structure_id as activity_id',
            'act.description AS activity'
        ])->from('attendance a')
            ->leftJoin('contact c', 'a.contact_id = c.id')
            ->leftJoin('event e', 'a.event_id = e.id')
            ->leftJoin('country pa', 'e.country_id= pa.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin(['pc' => $this->ProjectProductQuery()], 'pc.project_id = p.id');

        $this->AplicarFiltros($subquery);

        $subquery->groupBy(['e.id', 'c.id', 'act.description']);

        $query = (new Query());
        $query->select([
            'id',
            'name',
            'activity_id',
            'activity',
            "COUNT(case when sex = 'F' then 1 else NULL end) AS f",
            "COUNT(case when sex = 'M' then 1 else NULL end) AS m",
            "count(sex) as total",
        ])
            ->from(['q' => $subquery])
            ->groupBy(['id', 'name', 'activity_id', 'activity',])
            ->orderBy(['total' => SORT_DESC]);

        $data = $query->all();
        $result = [];
        foreach ($data as $d) {
            $result[$d['activity_id']][] = $d;
        }
        return $result;
    }

    public function actionGraficoTipoParticipante()
    {
        $this->validacionPost();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->ParticipantesTipoData();
    }

    private function ParticipantesTipoData()
    {
        $subquery = $this->ParticipantesTipoSexoSubquery();
        $query = (new Query());
        $query
            ->select([
                'type',
                "COUNT(case when sex = 'F' then 1 else NULL end) AS f",
                "COUNT(case when sex = 'M' then 1 else NULL end) AS m",
                "count(sex) as total",
            ])
            ->from(['q' => $subquery])
            ->groupBy(['type'])
            ->orderBy(['total' => SORT_DESC]);
        return $query->all();
    }

    private function ParticipantesTipoSexoSubquery()
    {
        $subquery = (new Query());
        $subquery->select([
            'c.type_id',
            "COALESCE(t.name,'NE') as type",
            'c.sex',
        ])->from('attendance a')
            ->leftJoin('contact c', 'a.contact_id = c.id')
            ->leftJoin('data_list t', 'c.type_id = t.id')
            ->leftJoin('event e', 'a.event_id = e.id')
            ->leftJoin('country pa', 'e.country_id= pa.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin(['pc' => $this->ProjectProductQuery()], 'pc.project_id = p.id');

        $this->AplicarFiltros($subquery);
        $subquery->groupBy(['c.type_id', 'c.id', 't.name']);
        return $subquery;
    }

    public function actionGraficoSexoParticipante()
    {
        $this->validacionPost();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->ParticipantesSexoData();
    }

    private function ParticipantesSexoData()
    {
        $subquery = $this->ParticipantesTipoSexoSubquery();
        $queryTotal = (new Query());
        $queryTotal
            ->select([
                "COUNT(case when sex = 'F' then 1 else NULL end) AS f",
                "COUNT(case when sex = 'M' then 1 else NULL end) AS m",
                "count(sex) as total",
            ])
            ->from(['q' => $subquery]);
        $result = $queryTotal->one();
        //        $result['total'] = $queryTotal->one();
        return $result;

    }

    private function getRubrosDbCantidad()
    {
        if ($this->_rubros_db_cantidad === null)
            $this->_rubros_db_cantidad = count($this->getRubrosDB());
        return $this->_rubros_db_cantidad;
    }

    private function getPaisesDbCantidad()
    {
        if ($this->_paises_db_cantidad === null)
            $this->_paises_db_cantidad = count($this->getPaisesDB());
        return $this->_paises_db_cantidad;
    }
    /*INICIO DE FUNCIONES AUXILIARES PARA DATA PARA DASHBOARD*/
}
