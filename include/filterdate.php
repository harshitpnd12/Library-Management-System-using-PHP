<!-- filter start -->
<div class="row">
  <div class="col-md-7">
    <form action="" method="GET">
      <div class="row">
        <div class="col-md-4">
          <input type="date" name="date1" value="<?= isset($_GET['date1']) ? $_GET['date1'] : '' ?>" class="form-control">
        </div>


        <div class="col-md-4">
          <input type="date" name="date2" value="<?= isset($_GET['date2']) ? $_GET['date2'] : '' ?>" class="form-control">
        </div>

        <div class="col-md-4">
          <button type="submit" class="btn btn-primary">Filter</button>
          <a href="booksrecord.php" class="btn btn-danger">Reset</a>
        </div>

      </div>
    </form>
  </div>
</div>
<!-- filter end -->