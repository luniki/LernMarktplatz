<table class="default">
    <tbody>
        <? foreach ($courses as $course) : ?>
        <tr>
            <td><?= htmlReady($course['name']) ?></td>
            <td>
                <form action="<?= PluginEngine::getLink($plugin, array(), "market/add_to_course/".$material->getId()) ?>" method="post">
                    <button name="seminar_id" value="<?= htmlReady($course->getId()) ?>" style="border: none; background: none; cursor: pointer;">
                        <?= Assets::img("icons/20/blue/add", array('class' => "text-bottom")) ?>
                    </button>
                </form>
            </td>
        </tr>
        <? endforeach ?>
    </tbody>
</table>