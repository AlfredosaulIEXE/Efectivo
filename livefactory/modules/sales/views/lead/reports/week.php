<div class="row">
    <div class="col-lg-12">
        <br>
        <table class="table table-bordered" style="text-transform: uppercase">
            <thead>
            <tr>
                <th></th>
                <?php foreach ($days as $date): ?>
                    <th><?=$week[$date->format('N')]?></th>
                <?php endforeach; ?>
                <th>Acum</th>
                <th>Optimo</th>
                <th>Real</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><strong>Leads</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <?php $acum_leads += $stat['leads']; ?>
                    <td><?=$stat['leads']?></td>
                <?php endforeach; ?>
                <td><?=$acum_leads?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><strong>Citas</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <?php $acum_appointments += $stat['appointments']; ?>
                    <td><?=$stat['appointments']?></td>
                <?php endforeach; ?>
                <td><?=$acum_appointments?></td>
                <td><?=$optim_appointments?>%</td>
                <td><?=number_format((($acum_appointments / $acum_leads) * 100), 2)?>%</td>
            </tr>
            <tr>
                <td colspan="<?=$total + 1?>"></td>
            </tr>
            <?php foreach ($leadSource as $mean_id => $mean):?>
                <tr>
                    <td><strong>UPS <?=$mean?></strong></td>
                    <?php foreach ($stats as $day => $stat): ?>
                        <?php
                        if ( ! isset($total_ups[$day])) {
                            $total_ups[$day] = 0;
                        }
                        $total_ups[$day] += $stat['ups'][$mean_id];
                        ?>
                        <td><?=$stat['ups'][$mean_id]?></td>
                    <?php endforeach; ?>
                    <td colspan="3">&nbsp;</td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td><strong>Total UPS</strong></td>
                <?php foreach ($total_ups as $day => $num): ?>
                    <?php $acum_ups += $num;  ?>
                    <td><strong><?=$num?></strong></td>
                <?php endforeach; ?>
                <td><?=$acum_ups?></td>
                <td><?=$optim_ups?>%</td>
                <td><?=number_format((($acum_ups / $acum_appointments) * 100), 2)?>%</td>
            </tr>
            <tr>
                <td><strong>UPS ReAgendadas</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <?php $acum_re_appointments += $stat['re_appointments']; ?>
                    <td><?=$stat['re_appointments']?></td>
                <?php endforeach; ?>
                <td><?=$acum_re_appointments?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>Clientes c/Incrementos</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <?php $acum_increments += $stat['increments']; ?>
                    <td><?=$stat['increments']?></td>
                <?php endforeach; ?>
                <td><?=$acum_increments?></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>Contratos</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <?php $acum_contracts += $stat['contracts']; ?>
                    <td><?=$stat['contracts']?></td>
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
                    <td>$<?=number_format($stat['amount'], 2)?></td>
                <?php endforeach; ?>
                <td class="text-success">$<?=number_format($advance_amount, 2)?></td>
                <td colspan="2">$<?=number_format($office_goal, 2)?></td>
            </tr>
            <tr>
                <td><strong>Venta</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <?php $acum_sales += $stat['sales']; ?>
                    <td><strong>$<?=number_format($stat['sales'], 2)?></strong></td>
                <?php endforeach; ?>
                <td class="text-success">$<?=number_format($acum_sales, 2)?></td>
                <td colspan="2">$<?=number_format($office_goal * 0.07, 2)?></td>
            </tr>
            <?php foreach ($paymentTypes as $key => $type):?>
                <tr>
                    <td><strong><?=$type?></strong></td>
                    <?php foreach ($stats as $day => $stat): ?>
                        <?php $advance_news = ($key == 'new_contract') ? ($advance_news + $stat[$key]) : $advance_news; ?>
                        <td>$<?=number_format($stat[$key], 2)?></td>
                    <?php endforeach; ?>
                    <?php if ($key == 'new_contract'): ?>
                        <td class="text-success">$<?=number_format($advance_news, 2)?></td>
                        <td colspan="2">$<?=number_format($office_goal * 0.07, 2)?></td>
                    <?php else: ?>
                        <td colspan="3">&nbsp;</td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td><strong>Total Ingreso</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <?php $acum_income += $stat['income']; ?>
                    <td><strong>$<?=number_format($stat['income'], 2)?></strong></td>
                <?php endforeach; ?>
                <td class="text-success"><strong>$<?=number_format($acum_income, 2)?></strong></td>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td><strong>Ademdums de Hoy</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <td>$<?=number_format($stat['addendums2'], 2)?></td>
                <?php endforeach; ?>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td><strong>Acum. Ademdums x Cobrar</strong></td>
                <?php foreach ($stats as $day => $stat): ?>
                    <td>$<?=number_format($stat['addendums3'], 2)?></td>
                <?php endforeach; ?>
                <td colspan="3">&nbsp;</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
