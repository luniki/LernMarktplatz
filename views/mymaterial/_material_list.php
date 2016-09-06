<table class="default">
    <thead>
    <tr>
        <th><?= _("Material") ?></th>
        <th><?= _("Bewertung") ?></th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <? $starwidth = "20px" ?>
    <? foreach ($materialien as $material) : ?>
        <tr>
            <td>
                <a href="<?= PluginEngine::getLink($plugin, array(), "market/details/".$material->getId()) ?>">
                    <?= htmlReady($material['name']) ?>
                </a>
            </td>
            <td>
                <? if ($material['rating'] === null) : ?>
                    <?= Assets::img($plugin->getPluginURL()."/assets/star.svg", array('width' => $starwidth)) ?>
                    <?= Assets::img($plugin->getPluginURL()."/assets/star.svg", array('width' => $starwidth)) ?>
                    <?= Assets::img($plugin->getPluginURL()."/assets/star.svg", array('width' => $starwidth)) ?>
                    <?= Assets::img($plugin->getPluginURL()."/assets/star.svg", array('width' => $starwidth)) ?>
                    <?= Assets::img($plugin->getPluginURL()."/assets/star.svg", array('width' => $starwidth)) ?>
                <? else : ?>
                    <? $material['rating'] = round($material['rating'], 1) / 2 ?>
                    <? $v = $material['rating'] >= 0.75 ? 3 : ($material['rating'] >= 0.25 ? 2 : "") ?>
                    <?= Assets::img($plugin->getPluginURL()."/assets/star$v.svg", array('width' => $starwidth)) ?>
                    <? $v = $material['rating'] >= 1.75 ? 3 : ($material['rating'] >= 1.25 ? 2 : "") ?>
                    <?= Assets::img($plugin->getPluginURL()."/assets/star$v.svg", array('width' => $starwidth)) ?>
                    <? $v = $material['rating'] >= 2.75 ? 3 : ($material['rating'] >= 2.25 ? 2 : "") ?>
                    <?= Assets::img($plugin->getPluginURL()."/assets/star$v.svg", array('width' => $starwidth)) ?>
                    <? $v = $material['rating'] >= 3.75 ? 3 : ($material['rating'] >= 3.25 ? 2 : "") ?>
                    <?= Assets::img($plugin->getPluginURL()."/assets/star$v.svg", array('width' => $starwidth)) ?>
                    <? $v = $material['rating'] >= 4.75 ? 3 : ($material['rating'] >= 4.25 ? 2 : "") ?>
                    <?= Assets::img($plugin->getPluginURL()."/assets/star$v.svg", array('width' => $starwidth)) ?>
                <? endif ?>
            </td>
            <td>
                <? if ($material['user_id'] === $GLOBALS['user']->id) : ?>
                    <a href="<?= PluginEngine::getLink($plugin, array(), "mymaterial/edit/".$material->getId()) ?>" data-dialog title="<?= _("Lernmaterial bearbeiten") ?>">
                        <?= Assets::img("icons/20/blue/edit") ?>
                    </a>
                <? endif ?>
            </td>
        </tr>
    <? endforeach ?>
    </tbody>
</table>