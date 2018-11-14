<?php

use kartik\form\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Json;

/* @var array $projects */
/* @var array $organizations */
/* @var array $countries */
/* @var integer|null $projectId */
/* @var integer|null $organizationId */
/* @var string|null $countryCode */

?>
<div class="row">
    <div class="col-lg-3">
        <input
                class="form-control"
                v-model="modelFilter.nameSearch"
                placeholder="--nombre--"
        />
    </div>
    <div class="col-lg-3">
        <combo-select2
                :options="list_projects"
                :value="modelFilter.projectId"
                prompt="-- Proyectos --"
                @input="modelFilter.projectId = $event">
        </combo-select2>
    </div>
    <div class="col-lg-2">
        <combo-select2
                :options="list_organizations"
                :value="modelFilter.organizationId"
                prompt="-- Organizaciones --"
                @input="modelFilter.organizationId = $event">
        </combo-select2>
    </div>
    <div class="col-lg-2">
        <combo-select2
                :options="list_countries"
                :value="modelFilter.countryCode"
                prompt="-- Países --"
                @input="modelFilter.countryCode = $event">
        </combo-select2>
    </div>
    <div class="col-lg-2">
        <button class="btn btn-primary" @click="btnFiltrarClick">
            <i class="fa fa-filter"></i> Filtrar
        </button>
        <button class="btn btn-primary" @click="btnLimpiarFiltroClick">
            <i class="fa fa-ban"></i> Limpiar
        </button>
    </div>
</div>