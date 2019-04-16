<?php

namespace app\controllers;

use app\components\UString;
use app\models\Activity;
use Yii;
use yii\helpers\ArrayHelper;

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
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $idProject = \Yii::$app->request->post('proyecto');
        $proyecto = \app\models\Project::findOne($idProject);
        return ['proyecto' => $proyecto ? $proyecto->ToArrayString(['colores']) : null,];
    }

    public function actionCantidadProyectos()
    {
        $this->validacionPost();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['proyectos' => $this->cantidadProyectos(),];
    }

    private function cantidadProyectos()
    {
        $subquery = (new \yii\db\Query());
        $subquery->select('count(distinct p.id)')
            ->from('event e')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin('project_contact pc', 'pc.project_id = p.id');

        $this->AplicarFiltros($subquery);
        return $subquery->scalar();
    }

    private function AplicarFiltros(&$query)
    {
        /* @var $query \yii\db\Query */

        /*Rango de Fechas*/
        $request = \Yii::$app->request;
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
        $request = Yii::$app->request;
        $rubrosPost = $request->post('rubros');
        $todos = $request->post('rubros_todos');
        if ((int)$todos === (int)1)
            return true;
        if (!$rubrosPost && $this->getExistePost())
            return false;
        if ($rubrosPost && $this->getExistePost())
            if ($this->getRubrosPostCantidad() == 0)
                return false;
        $rubros = $this->getRubrosDbCantidad();
        $selecionados = 0;
        foreach ($this->Rubros() as $r)
            if ($r['active'] == true)
                $selecionados++;
        return $rubros == $selecionados;
    }

    private function getExistePost()
    {
        return \Yii::$app->request->post('post', 'false') != 'false';
    }

    private function getRubrosPostCantidad()
    {
        $request = \Yii::$app->request;
        $this->_rubros_post_cantidad = $request->post('rubros', []);
        return count($this->_rubros_post_cantidad);
    }

    private function getRubrosDbCantidad()
    {
        if ($this->_rubros_db_cantidad === null)
            $this->_rubros_db_cantidad = count($this->getRubrosDB());
        return $this->_rubros_db_cantidad;
    }

    private function getRubrosDB()
    {
        if ($this->_rubros_db === null) {
            $request = \Yii::$app->request;
            $subquery = (new \yii\db\Query());
            $subquery->select([
                "ifnull(product, 'N/E') as rubro, ifnull(product,0) as id, 'true' as active",
            ])->from('project_contact')
                ->where('product is not null')
                ->groupBy(["ifnull(product, 'N/E')"]);
            $proyecto = $request->post('proyecto');
            if ($proyecto)
                $subquery->andFilterWhere(['project_id' => $proyecto]);
            $this->_rubros_db = $subquery->all();
        }
        return $this->_rubros_db;
    }

    private function Rubros()
    {
        $result = $this->getRubrosDB();
        $rubros = $this->getRubros(0);

        $estado = $this->getRubrosNingunoSeleccionado() ? false : true;

        foreach ($result as $key => $value) {
            $result[$key]['active'] = (bool)$estado;
        }
        if ($rubros) {
            $data = [];
            foreach ($result as $value) {
                $value['active'] = (bool)false;
                $data[$value['id']] = $value;
            }
            foreach ($rubros as $p) {
                if (isset($data[$p]['rubro']))
                    $data[$p]['active'] = (bool)true;
            }

            return array_values($data);
        }
        return $result;
    }

    private function getRubros($value = '')
    {
        $request = \Yii::$app->request;
        $rubros = $request->post('rubros', []);
        foreach ($rubros as $k => $p) {
            if (empty($p) || $p == '')
                $rubros[$k] = $value;
        }
        return $rubros;
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

    private function getPaisesAlgunoSeleccionado()
    {
        return !$this->getPaisesTodosSeleccionados() && $this->getPaisesPostCantidad() > 0 && !$this->getPaisesNingunoSeleccionado();
    }

    private function getPaisesTodosSeleccionados()
    {

        $request = Yii::$app->request;
        $paisesPost = $request->post('paises');
        $todos = $request->post('paises_todos');
        if ((int)$todos === (int)1)
            return true;
        if (!$paisesPost && $this->getExistePost())
            return false;
        if ($paisesPost && $this->getExistePost())
            if ($this->getPaisesPostCantidad() == 0)
                return false;
        $paises = $this->getPaisesDbCantidad();
        $selecionados = 0;
        foreach ($this->Paises() as $r)
            if ($r['active'] == true)
                $selecionados++;
        return $paises == $selecionados;
    }

    private function getPaisesPostCantidad()
    {
        $request = \Yii::$app->request;
        $this->_paises_post_cantidad = $request->post('paises', []);
        return count($this->_paises_post_cantidad);
    }

    private function getPaisesDbCantidad()
    {
        if ($this->_paises_db_cantidad === null)
            $this->_paises_db_cantidad = count($this->getPaisesDB());
        return $this->_paises_db_cantidad;
    }

    private function getPaisesDb()
    {

        if (!$this->_paises_db) {
            $request = \Yii::$app->request;
            $subquery = (new \yii\db\Query());
            $subquery->select([
                "ifnull(t.value, 'N/E') as country, ifnull(t.id,0) as id, 'true' as active",
            ])->from('event e')
                ->leftJoin('data_list t', 'e.country_id = t.id')
                ->leftJoin('structure act', 'e.structure_id = act.id')
                ->leftJoin('project p', 'act.project_id = p.id')
                ->where('t.value is not null')
                ->groupBy(["ifnull(t.value, 'N/E')"]);
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

    private function Paises()
    {
        $paises = $this->getPaises(0);
        $result = $this->getPaisesDb();
        $estado = $this->getPaisesNingunoSeleccionado() ? false : true;
        foreach ($result as $key => $value) {
            $result[$key]['active'] = (bool)$estado;
        }

        if ($paises) {
            $data = [];
            foreach ($result as $value) {
                $value['active'] = (bool)false;
                $data[(int)$value['id']] = $value;
            }
            foreach ($paises as $p) {
                if (isset($data[$p]['country']))
                    $data[(int)$p]['active'] = (bool)true;
            }
            return array_values($data);
        }
        return $result;
    }

    private function getPaises($value = null)
    {
        $request = \Yii::$app->request;
        $paises = $request->post('paises', []);

        foreach ($paises as $k => $p) {
            if (empty($p) || $p == '')
                $paises[$k] = $value;
        }
        return $paises;
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

    public function actionCantidadEventos()
    {
        $this->validacionPost();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['cantidadEventos' => $this->cantidadEventos()];
    }

    private function cantidadEventos()
    {


        $subquery = (new \yii\db\Query());
        $subquery->from('event e')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin('project_contact pc', 'pc.project_id = p.id');

        $this->AplicarFiltros($subquery);

        return [
            'eventos' => $subquery->select('e.id')->groupBy('e.id')->count(),
            'actividades' => $subquery->select('e.structure_id')->groupBy('e.structure_id')->count(),
        ];
    }

    public function actionGraficoActividades()
    {
        $this->validacionPost();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['actividades' => $this->ParticipantesActividadPorSexo(),];
    }

    private function ParticipantesActividadPorSexo()
    {
        $subquery = (new \yii\db\Query());
        $subquery->select([
            'e.structure_id as activity_id',
            'if(p.id >0, concat(act.description, " / ", p.name),act.description) as name',
            'c.id',
            'c.sex'
        ])->from('attendance a')
            ->leftJoin('contact c', 'a.contact_id = c.id')
            ->leftJoin('event e', 'a.event_id = e.id')
            ->leftJoin('data_list pa', 'e.country_id= pa.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin('project_contact pc', 'pc.contact_id = c.id');

        $this->AplicarFiltros($subquery);

        $subquery->groupBy(['act.id', 'c.id']);

        $query = (new \yii\db\Query());
        $query
            ->select([
                'activity_id',
                'name',
                "COUNT(IF(sex = 'F', 1, NULL)) AS f",
                "COUNT(IF(sex = 'M', 1, NULL)) AS m",
                "count(sex) as total",
            ])
            ->from(['q' => $subquery])
            ->groupBy(['activity_id'])
            ->orderBy(['total' => SORT_DESC]);
        return $query->all();
    }

    public function actionPaises()
    {
        $this->validacionPost();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['paises' => $this->Paises(), 'todos' => (bool)$this->getPaisesTodosSeleccionados(), 'ninguno' => (bool)$this->getPaisesNingunoSeleccionado()];
    }

    public function actionRubros()
    {
        $this->validacionPost();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['rubros' => $this->Rubros(),];
    }

    public function actionGraficoOrganizaciones()
    {
        $this->validacionPost();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
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

    /*FIN DE DATA PARA DASHBOARD*/


    /*INICIO DE FUNCIONES AUXILIARES PARA DATA PARA DASHBOARD*/

    private function Organizaciones()
    {
        $subquery = (new \yii\db\Query());
        $subquery
            ->select(["distinct o.id, IFNULL(o.name,'NE') as name, IFNULL(t.id,'ne') parent",])
            ->from('event e')
            ->leftJoin('organization o', 'e.implementing_organization_id = o.id')
            ->leftJoin('data_list pa', 'e.country_id= pa.id')
            ->leftJoin('organization_type t', 'o.organization_type_id = t.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin('project_contact pc', 'pc.project_id = p.id');
        $this->AplicarFiltros($subquery);
        $subquery->andWhere('o.organization_id is null');
        return $subquery->all();
    }

    private function OrganizacionesTipo()
    {
        $subquery = (new \yii\db\Query());
        $subquery
            ->select(["distinct t.id as id, ifnull( t.name,'Sin Tipo') as name"])
            ->from('event e')
            ->leftJoin('organization o', 'e.implementing_organization_id = o.id')
            ->leftJoin('data_list pa', 'e.country_id= pa.id')
            ->leftJoin('organization_type t', 'o.organization_type_id = t.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin('project_contact pc', 'pc.project_id = p.id');
        $this->AplicarFiltros($subquery);
        $subquery->andWhere('o.organization_id is null');

        $query = (new \yii\db\Query());
        $query->select(["ifnull(id,'ne') as id, name "])->from(['q' => $subquery]);
        return $query->all();
    }

    public function actionProyectosMetas()
    {
        $this->validacionPost();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['proyectos_metas' => $this->metasProyectos(),];
    }

    private function metasProyectos()
    {
        $request = \Yii::$app->request;
        $proyecto = $request->post('proyecto');
        if (is_array($proyecto))
            if (count($proyecto) > 3) return [];
        if (!$proyecto) return [];
        $queryMetas = (new \yii\db\Query());
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
            $subquery = (new \yii\db\Query());
            $subquery
                ->select(['c.sex'])
                ->from('attendance a')
                ->leftJoin('contact c', 'a.contact_id = c.id')
                ->leftJoin('organization o', 'c.organization_id = o.id')
                ->leftJoin('data_list t', 'c.type_id = t.id')
                ->leftJoin('event e', 'a.event_id = e.id')
                ->leftJoin('structure act', 'e.structure_id = act.id')
                ->leftJoin('project p', 'act.project_id = p.id')
                ->leftJoin('project_contact pc', 'pc.contact_id = c.id')
                ->groupBy('a.contact_id');

            $this->AplicarFiltros($subquery);

            $queryTotal = (new \yii\db\Query());
            $queryTotal
                ->select([
                    "COUNT(IF(sex = 'F', 1, NULL)) AS f",
                    "COUNT(IF(sex = 'M', 1, NULL)) AS m",
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
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->ParticipantesPaisEventosData();
    }

    private function ParticipantesPaisEventosData()
    {
        $result = [];
        $request = \Yii::$app->request;
        $subquery = (new \yii\db\Query());
        $subquery->select([
            "ifnull(pa.value,'') as country",
            'ca.*',
            'e.id as eventos',
            "c.sex",
        ])->from('attendance a')
            ->leftJoin('contact c', 'a.contact_id = c.id')
            ->leftJoin('event e', 'a.event_id = e.id')
            ->leftJoin('data_list pa', 'e.country_id= pa.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin('project_contact pc', 'pc.contact_id = c.id')
            ->leftJoin('country_aux ca', 'pa.value = ca.alfa2');

        $this->AplicarFiltros($subquery);

        $subquery->groupBy(["e.id", 'c.id']);
        $query = (new \yii\db\Query());
        $query
            ->select([
                'pais_en_ingles',
                "count(sex) as total",
                "COUNT(IF(sex = 'F', 1, NULL)) AS f",
                "COUNT(IF(sex = 'M', 1, NULL)) AS m",
                'pais_en_espaniol',
                'coordenada_x',
                'coordenada_y',
                'country',
                'count(distinct (eventos)) as eventos'
            ])
            ->from(['q' => $subquery])
            ->groupBy(['country'])
            ->orderBy(['country' => SORT_ASC]);
        $paises = $query->all();
        $paisesArray = [];
        foreach ($paises as $p)
            if (!empty($p['country']))
                $paisesArray[] = [
                    $p['pais_en_ingles'],
                    (int)$p['total'],
                    (int)$p['f'],
                    (int)$p['m'],
                    (double)$p['coordenada_x'],
                    (double)$p['coordenada_y'],
                    $p['pais_en_espaniol'],
                    UString::lowerCase($p['country']),
                    (int)$p['eventos']
                ];

        $result['paisArray'] = $paisesArray;
        $result['pais'] = $paises;
        return $result;
    }

    public function actionGraficoNacionalidad()
    {
        $this->validacionPost();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->ParticipantesNacionalidadData();
    }

    private function ParticipantesNacionalidadData()
    {
        $result = [];
        $request = \Yii::$app->request;
        $subquery = (new \yii\db\Query());
        $subquery->select([
            "ifnull(c.country,'') as country",
            'ca.*',
            "c.sex",
        ])->from('attendance a')
            ->leftJoin('contact c', 'a.contact_id = c.id')
            ->leftJoin('event e', 'a.event_id = e.id')
            ->leftJoin('data_list pa', 'e.country_id= pa.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin('project_contact pc', 'pc.contact_id = c.id')
            ->leftJoin('country_aux ca', 'c.country = ca.alfa2');

        $this->AplicarFiltros($subquery);

        $subquery->groupBy(["c.id"]);
        $query = (new \yii\db\Query());
        $query
            ->select([
                'pais_en_ingles',
                "count(sex) as total",
                "COUNT(IF(sex = 'F', 1, NULL)) AS f",
                "COUNT(IF(sex = 'M', 1, NULL)) AS m",
                'pais_en_espaniol',
                'coordenada_x',
                'coordenada_y',
                'country',
            ])
            ->from(['q' => $subquery])
            ->groupBy(['country'])
            ->orderBy(['country' => SORT_ASC]);
        $paises = $query->all();
        $paisesArray = [];
        foreach ($paises as $p)
            if (!empty($p['country']))
                $paisesArray[] = [
                    $p['pais_en_ingles'],
                    (int)$p['total'],
                    (int)$p['f'],
                    (int)$p['m'],
                    (double)$p['coordenada_x'],
                    (double)$p['coordenada_y'],
                    $p['pais_en_espaniol'],
                    UString::lowerCase($p['country'])
                ];

        $result['paisArray'] = $paisesArray;
        $result['pais'] = $paises;
        return $result;
    }

    public function actionGraficoAnioFiscal()
    {
        $this->validacionPost();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->ParticipantesFiscalData();
    }

    private function ParticipantesFiscalData()
    {
        $result = [];
        $request = \Yii::$app->request;
        $mes = (int)$request->post('mes_fiscal', 10);
        $subquery = (new \yii\db\Query());
        /*
          Gráfico de año fiscal de modo que
            Si el mes de inicio es del primer semestre se conserva el año,
                    siempre y cuando el mes de inicio sea menor que el mes a validar
            Si el mes de inicio es del segundo semestre se moverá al año siguiente,
                    siempre y cuando el mes de inicio sea mayor que el mes que se está validando
        */
        $subquery->select([
            "IF($mes<=6," .
            "	IF($mes<=month(e.start)," .
            "		year(e.start)," .
            "		year(e.start)-1" .
            "	)," .
            "	IF($mes > month(e.start)," .
            "		year(e.start)," .
            "		year(e.start)+1" .
            "	)" .
            "  ) as type",
            "c.sex",
        ])->from('attendance a')
            ->leftJoin('contact c', 'a.contact_id = c.id')
            ->leftJoin('event e', 'a.event_id = e.id')
            ->leftJoin('data_list pa', 'e.country_id= pa.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin('project_contact pc', 'pc.contact_id = c.id');

        $this->AplicarFiltros($subquery);

        $subquery->groupBy(["c.id"]);
        $query = (new \yii\db\Query());
        $query
            ->select([
                'type',
                "COUNT(IF(sex = 'F', 1, NULL)) AS f",
                "COUNT(IF(sex = 'M', 1, NULL)) AS m",
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
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->ParticipantesEdadData();
    }

    private function ParticipantesEdadData()
    {
        $result = [];
        $subquery = (new \yii\db\Query());
        $subquery->select([
            "IFNULL( f.name, 'N/E' ) as type",
            "c.sex",
        ])->from('attendance a')
            ->leftJoin('contact c', 'a.contact_id = c.id')
            ->leftJoin('filter f', 'f.slug = "age" and f.filter_id is not null and TIMESTAMPDIFF( YEAR, birthdate, CURDATE( ) ) between f.start and f.end')
            ->leftJoin('event e', 'a.event_id = e.id')
            ->leftJoin('data_list pa', 'e.country_id= pa.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin('project_contact pc', 'pc.contact_id = c.id');

        $this->AplicarFiltros($subquery);

        $subquery->groupBy(["a.contact_id"]);
        $query = (new \yii\db\Query());
        $query
            ->select([
                'type',
                "COUNT(IF(sex = 'F', 1, NULL)) AS f",
                "COUNT(IF(sex = 'M', 1, NULL)) AS m",
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
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->ParticipantesEducacionData();
    }

    private function ParticipantesEducacionData()
    {
        $result = [];

        $subquery = (new \yii\db\Query());
        $subquery->select([
            "IFNULL( edu.name, 'N/E' ) as type",
            "c.sex",
        ])->from('attendance a')
            ->leftJoin('contact c', 'a.contact_id = c.id')
            ->leftJoin('event e', 'a.event_id = e.id')
            ->leftJoin('data_list edu', 'c.education_id = edu.id')
            ->leftJoin('data_list pa', 'e.country_id= pa.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin('project_contact pc', 'pc.contact_id = c.id');

        $this->AplicarFiltros($subquery);

        $subquery->groupBy(["a.contact_id"]);
        $query = (new \yii\db\Query());
        $query
            ->select([
                'type',
                "COUNT(IF(sex = 'F', 1, NULL)) AS f",
                "COUNT(IF(sex = 'M', 1, NULL)) AS m",
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
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return ['eventos' => $this->ParticipantesEventosPorSexo()];
    }

    private function ParticipantesEventosPorSexo()
    {
        $subquery = (new \yii\db\Query());
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
            ->leftJoin('data_list pa', 'e.country_id= pa.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin('project_contact pc', 'pc.contact_id = c.id');

        $this->AplicarFiltros($subquery);

        $subquery->groupBy(['e.id', 'c.id']);

        $query = (new \yii\db\Query());
        $query->select([
            'id',
            'name',
            'activity_id',
            'activity',
            "COUNT(IF(sex = 'F', 1, NULL)) AS f",
            "COUNT(IF(sex = 'M', 1, NULL)) AS m",
            "count(sex) as total",
        ])
            ->from(['q' => $subquery])
            ->groupBy(['id'])
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
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->ParticipantesTipoData();
    }

    private function ParticipantesTipoData()
    {
        $subquery = $this->ParticipantesTipoSexoSubquery();
        $query = (new \yii\db\Query());
        $query
            ->select([
                'type',
                "COUNT(IF(sex = 'F', 1, NULL)) AS f",
                "COUNT(IF(sex = 'M', 1, NULL)) AS m",
                "count(sex) as total",
            ])
            ->from(['q' => $subquery])
            ->groupBy(['type'])
            ->orderBy(['total' => SORT_DESC]);
        return $query->all();
    }


    public function actionGraficoSexoParticipante()
    {
        $this->validacionPost();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->ParticipantesSexoData();
    }

    private function ParticipantesSexoData()
    {
        $subquery = $this->ParticipantesTipoSexoSubquery();
        $queryTotal = (new \yii\db\Query());
        $queryTotal
            ->select([
                "COUNT(IF(sex = 'F', 1, NULL)) AS f",
                "COUNT(IF(sex = 'M', 1, NULL)) AS m",
                "count(sex) as total",
            ])
            ->from(['q' => $subquery]);
        $result=$queryTotal->one();
//        $result['total'] = $queryTotal->one();
        return $result;

    }


    private function ParticipantesTipoSexoSubquery()
    {
        $subquery = (new \yii\db\Query());
        $subquery->select([
            'c.type_id',
            'IFNULL(t.name,"NE") as type',
            'c.sex',
            'e.start'
        ])->from('attendance a')
            ->leftJoin('contact c', 'a.contact_id = c.id')
            ->leftJoin('data_list t', 'c.type_id = t.id')
            ->leftJoin('event e', 'a.event_id = e.id')
            ->leftJoin('data_list pa', 'e.country_id= pa.id')
            ->leftJoin('structure act', 'e.structure_id = act.id')
            ->leftJoin('project p', 'act.project_id = p.id')
            ->leftJoin('project_contact pc', 'pc.contact_id = c.id');

        $this->AplicarFiltros($subquery);
        $subquery->groupBy(['c.type_id', 'c.id']);
        return $subquery;
    }
    /*INICIO DE FUNCIONES AUXILIARES PARA DATA PARA DASHBOARD*/
}
