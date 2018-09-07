<section class="row">
    <div class="col-lg-6 col-md-6 col-sm-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Paises</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn" ng-class="{'btn-success':formulario.paises_todos}"
                            ng-click="PaisesTodos()">Todos
                    </button>
                    <button type="button" class="btn" ng-class="{'btn-danger':formulario.paises_ninguno}"
                            ng-click="PaisesNinguno()">Ninguno
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4" ng-repeat="r in paises">
                        <div class="searchable-container">
                            <div ng-click="selectedPaisClick(r)" class="btn-group bizmoduleselect">
                                <label class="btn btn-secondary {{classChecked(r)}} btn-color-1">
                                    <div class="bizcontent">
                                        <input style="display: none" type="checkbox" name="paises[]"
                                               autocomplete="off" ng-model="r.active"
                                               value="{{r.country}}" class="d-none"
                                               ng-checked="r.active">
<!--                                        <i class="fa {{iconChecked(r)}}"></i>-->
                                        <h5>{{r.country}}</h5>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Rubros</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn" ng-class="{'btn-success':formulario.rubros_todos}"
                            ng-click="RubrosTodos()">Todos
                    </button>
                    <button type="button" class="btn" ng-class="{'btn-danger':formulario.rubros_ninguno}"
                            ng-click="RubrosNinguno()">Ninguno
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-4" ng-repeat="r in rubros">
                        <div class="searchable-container">
                            <div ng-click="selectedRubroClick(r)" class="btn-group bizmoduleselect">
                                <label class="btn btn-secondary {{classChecked(r)}} btn-color-2">
                                    <div class="bizcontent">
                                        <input style="display: none" type="checkbox" name="rubros[]"
                                               autocomplete="off" ng-model="r.active"
                                               value="{{r.rubro}}" class="d-none"
                                               ng-checked="r.active">
<!--                                        <i class="fa {{iconChecked(r)}}"></i>-->
                                        <h5>{{r.rubro}}</h5>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>