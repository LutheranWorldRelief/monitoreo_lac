<section class="row">
    <div class="box">
        <div class="box-header with-border">
	<h3 class="box-title"><?= \Yii::t('app', "Tarjetas Resumen")?></h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body" style="background-color: #ECF0F5;">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="col-lg-3 col-md-3 col-sm-3" ng-if="!(proyecto !== null)">
                    <div class="info-box">
                            <span class="info-box-icon bg-blue"><i class="fa fa-folder-open-o"
                                                                   aria-hidden="true"></i></span>
                        <div class="info-box-content">
                            <br>
			    <span class="info-box-text"><?= \Yii::t('app', "Proyectos")?></span>
                            <span class="info-box-number">{{proyectos}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-check-square-o"
                                                                aria-hidden="true"></i></span>
                        <div class="info-box-content">
                            <br>
			    <span class="info-box-text"><?= \Yii::t('app', "Actividades")?></span>
                            <span class="info-box-number">{{eventos.actividades}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <div class="info-box">
                            <span class="info-box-icon bg-blue"><i class="fa fa-calendar-check-o"
                                                                   aria-hidden="true"></i></span>
                        <div class="info-box-content">
                            <br>
			    <span class="info-box-text"><?= \Yii::t('app', "Eventos")?></span>
                            <span class="info-box-number">{{eventos.eventos}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">


                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-list" aria-hidden="true"></i></span>
                        <div class="info-box-content">
                            <br>
			    <span class="info-box-text"><?= \Yii::t('app', "Rubros")?></span>
                            <span class="info-box-number">{{CantidadRubros()}}</span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
