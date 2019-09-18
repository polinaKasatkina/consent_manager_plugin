<div class="content-block">
    <p><strong>What data do we store about you?</strong></p>

    <div class="text-content">
        <?=get_option('cm_personal_data')?>
    </div>
</div>

<div class="content-block">
    <p><strong>How is the data used?</strong></p>

    <div class="text-content">
        <?=get_option('cm_how_data_used')?>
    </div>
</div>

<div class="content-block">
    <p><strong>Get a Copy of Your Data</strong></p>
    <p>Download a copy of your data. A file is created in CSV format.</p>

    <button class="btn" id="download_personal_data">Download Personal Data</button>
</div>

<div class="content-block">
    <p><strong>Delete Your Data</strong></p>
    <p>You can choose to delete all your personal data. Click the button below to delete, you will be asked for confirmation.</p>

    <button class="btn" id="delete_personal_data">Delete Personal Data</button>
</div>
