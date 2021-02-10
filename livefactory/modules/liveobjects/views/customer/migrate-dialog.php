<div id="migrate-dialog" class="modal fade">
    <form method="post" action="index.php?r=sales/lead/migrate&id=<?=$_REQUEST['id']?>">

            <?php Yii::$app->request->enableCsrfValidation = true; ?>

            <input type="hidden" name="_csrf" value="<?php echo $this->renderDynamic('return Yii::$app->request->csrfToken;'); ?>">

            <div class="modal-dialog modal-sm" style="width: 420px!important;">

                <div class="modal-content">

                    <div class="modal-header">

                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

                        <h4 class="modal-title">Migrar Lead</h4>

                    </div>

                    <div class="modal-body">

                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="control-label col-md-4">Gestor:</label>
                                <div class="col-md-8">
                                    <select name="service_owner_id" class="form-control">
                                        <option value="">Seleccionar...</option>
                                        <?php foreach ($manager as $id => $manage): ?>
                                            <option value="<?=$id?>"><?=$manage?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary btn-sm">MIGRAR AHORA</button>
                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancelar</button>

                    </div>

                </div><!-- /.modal-content -->

            </div><!-- /.modal-dialog -->

        </form>

</div>