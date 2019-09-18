<form method="get">
    <input type="hidden" name="page" value="classes_list"/>
    <label class="screen-reader-text" for="post-search-input">Search:</label>
    <input type="search" id="post-date-picker" name="date" value="<?=isset($_GET['date']) ? $_GET['date'] : ''?>">
    <input type="submit" id="search-submit" class="button" value="Search">
</form>
