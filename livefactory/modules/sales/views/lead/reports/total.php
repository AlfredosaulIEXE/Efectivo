<?php
$acum_upsa = [];
$acum_salesa = [];
$acum_adem = 0;
$acum_cxc = 0;
?>
<div class="row">
    <div class="col-lg-12">
        <br>
        <table class="table table-bordered" style="text-transform: uppercase">
            <thead>
            <tr>
                <th></th>
                <th><?=date('d/m/Y', strtotime($start))?> - <?=date('d/m/Y', strtotime($end))?></th>
                <th>Optimo</th>
                <th>Real</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><strong>Leads</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <?php $acum_leads += $stat['leads']; ?>
                <?php endforeach; ?>
                <td><?=$acum_leads?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><strong>Citas</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <?php $acum_appointments += $stat['appointments']; ?>
                <?php endforeach; ?>
                <td><?=$acum_appointments?></td>
                <td><?=$optim_appointments?>%</td>
                <td><?=number_format((($acum_appointments / $acum_leads) * 100), 2)?>%</td>
            </tr>
            <tr>
                <td colspan="<?=$total + 1?>"></td>
            </tr>
            <?php foreach ($leadSource as $mean_id => $mean):?>
                <?php $acum_upsa[$mean_id] = 0; ?>
                <tr>
                    <td><strong>UPS <?=$mean?></strong></td>
                    <?php foreach ($stats as $day => $stat): ?>
                        <?php
                        $acum_upsa[$mean_id] += $stat['ups'][$mean_id];
                        $acum_ups += $stat['ups'][$mean_id];
                        ?>
                    <?php endforeach; ?>
                    <td><?=$acum_upsa[$mean_id]?></td>
                    <td colspan="3">&nbsp;</td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td><strong>Total UPS</strong></td>
                <td><?=$acum_ups?></td>
                <td><?=$optim_ups?>%</td>
                <td><?=number_format((($acum_ups / $acum_appointments) * 100), 2)?>%</td>
            </tr>
            <tr>
                <td><strong>UPS ReAgendadas</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <?php $acum_re_appointments += $stat['re_appointments']; ?>
                <?php endforeach; ?>
                <td><?=$acum_re_appointments?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>Clientes c/Incrementos</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <?php $acum_increments += $stat['increments']; ?>
                <?php endforeach; ?>
                <td><?=$acum_increments?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>Contratos</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <?php $acum_contracts += $stat['contracts']; ?>
                <?php endforeach; ?>
                <td><?=$acum_contracts?></td>
                <td><?=$optim_contracts?>%</td>
                <td><?=number_format((($acum_contracts / $acum_ups) * 100), 2)?>%</td>
            </tr>
            <tr>
                <td colspan="<?=$total - 2?>"></td>
                <td><strong>Avance</strong></td>
                <td colspan="2"><strong>Meta</strong></td>
            </tr>
            <tr>
                <td><strong>Monto</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <?php $advance_amount += $stat['amount']; ?>
                <?php endforeach; ?>
                <td class="text-success">$<?=number_format($advance_amount, 2)?></td>
                <td colspan="2">$<?=number_format($office_goal, 2)?></td>
            </tr>
            <tr>
                <td><strong>Venta</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <?php $acum_sales += $stat['sales']; ?>
                <?php endforeach; ?>
                <td class="text-success">$<?=number_format($acum_sales, 2)?></td>
                <td colspan="2">$<?=number_format($office_goal * 0.07, 2)?></td>
            </tr>
            <?php foreach ($paymentTypes as $key => $type):?>
                <?php $acum_salesa[$key] = 0; ?>
                <tr>
                    <td><strong><?=$type?></strong></td>
                    <?php foreach ($stats as $day => $stat): ?>
                        <?php $acum_salesa[$key] += $stat[$key]; ?>
                    <?php endforeach; ?>
                    <td class="text-success">$<?=number_format($acum_salesa[$key], 2)?></td>
                    <td colspan="2">&nbsp;</td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td><strong>Total Ingreso</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <?php $acum_income += $stat['income']; ?>
                <?php endforeach; ?>
                <td class="text-success"><strong>$<?=number_format($acum_income, 2)?></strong></td>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td><strong>Ademdums de Hoy</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                <?php $acum_adem += $stat['addendums2']; ?>
                <?php endforeach; ?>
                <td>$<?=number_format($acum_adem, 2)?></td>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td><strong>Acum. Ademdums x Cobrar</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <?php $acum_cxc += $stat['addendums3']; ?>
                <?php endforeach; ?>
                <td>$<?=number_format($stat['addendums3'], 2)?></td>
                <td colspan="3">&nbsp;</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>