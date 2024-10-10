<?php 
    echo $HTML->title_panel([
            'heading' => $Lang->get('Delete route'),
        ]); 

    echo $Form->form_start();

    $Alert->set('warning', $Lang->get('Are you sure you wish to delete this route?'));
    echo $Alert->output();

    echo $HTML->submit_bar([
                'button' => $Form->submit('btnsubmit', 'Delete', 'button'),
                'cancel_link' => '/core/apps/content/routes/'
            ]);
    echo $Form->form_end();
