<?php if(COUNT($lista_periodos) > 0): ?>
<div class="modal fade" id="periodo_letivo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Selecione o período letivo</h5>
            </div>
            <input type='hidden' id='controller' value='<?php echo $controller; ?>'/>
            <div class="modal-body" id="modal_periodos">
                <?php
                for ($i = 0; $i < COUNT($lista_periodos); $i ++) 
                { 
                    echo "<div class='form-group'>";
                        echo "<div class='checkbox checbox-switch switch-success custom-controls-stacked'>";
                            echo "<label for='periodo$i' style='color: #8a8d93;'>";
                                echo "<input onchange='Main.set_curso_periodo(".$lista_periodos[$i]['Periodo_letivo_id'].",".$lista_periodos[$i]['Curso_id'].");' type='checkbox' id='periodo$i' name='periodo$i' value='1' />";
                                echo"<span></span> ".$lista_periodos[$i]['Curso'];
                            echo "</label>";
                        echo "</div>";
                    echo"</div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php endif;?>

<?php if(empty($this->input->cookie('periodo_letivo_id'))): ?>
<script type="text/javascript">
    window.onload = function()
    {
        $('#periodo_letivo').modal({
        keyboard: false,
        backdrop : 'static',
        });
    }
</script>
<?php endif;?>

<?php $this->load->view("/shared/_periodo"); ?>