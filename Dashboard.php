<?php
$BASE_URL = "http://localhost/library/";
$dir_url = "C:/xampp/htdocs/library/";
include_once($dir_url . "configuration/config.php");
include_once($dir_url . "models/dashboards.php");
include_once($dir_url . "include/middleware.php");
include_once($dir_url . "configuration/db.php");

$counts = getCounts($conn);
$tabs = getTabData($conn);

include_once($dir_url . "include/header.php");
include_once($dir_url . "include/topbar.php");
include_once($dir_url . "include/sidebar.php");
?>
<main class="mt-5 pt-3" id="Dashboard">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <h4 class="fw-bold text-uppercase mt-4">Dashboard</h4>
        <p>Statistics of the Library</p>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body text-center">
            <h5 class="card-title text-uppercase text-muted">Total Books</h5>
            <h1><?php echo $counts['total_books']; ?></h1>
            <p><a href="books.php" class="link-underline-light">view more</a></p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body text-center">
            <h5 class="card-title text-uppercase text-muted">Total Users</h5>
            <h1><?php echo $counts['total_users']; ?></h1>
            <p><a href="users.php" class="link-underline-light">view more</a></p>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <div class="card-body text-center">
            <h5 class="card-title text-uppercase text-muted">Total Books Issue</h5>
            <h1><?php echo $counts['total_issuebooks']; ?></h1>
            <p><a href="issuebooks.php" class="link-underline-light">view more</a></p>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-5">
      <div class="col-md-12">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link text-uppercase active" id="recent-issue-tab" data-bs-toggle="tab" data-bs-target="#recent-issue-tab-pane" type="button" role="tab" aria-controls="recent-issue-tab-pane" aria-selected="true">
              Recent issues Book
            </button>
          </li>

          <li class="nav-item" role="presentation">
            <button class="nav-link text-uppercase" id="recentuser-tab" data-bs-toggle="tab" data-bs-target="#recentuser-pane" type="button" role="tab" aria-controls="recentuser-pane" aria-selected="false">
              Recent user
            </button>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade show active" id="recent-issue-tab-pane" role="tabpanel" aria-labelledby="recent-issue-tab" tabindex="0">
            <table class="table">
              <thead class="table-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Book Name</th>
                  <th scope="col">User Name</th>
                  <th scope="col">Issue Date</th>
                  <th scope="col">Return Date</th>
                  <th scope="col">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 1;
                foreach ($tabs['issuebooks'] as $ib) {
                ?>
                  <tr>
                    <th><?php echo $i++; ?></th>
                    <td><?php echo htmlspecialchars($ib['book_title']); ?></td>
                    <td><?php echo htmlspecialchars($ib['user_name']); ?></td>
                    <td><?php echo date("d-m-y", strtotime($ib['issue_date'])); ?></td>
                    <td><?php echo date("d-m-y", strtotime($ib['return_date'])); ?></td>
                    <td>
                      <?php if ($ib['is_return'] == 1) {
                        echo '<span class="badge text-bg-success">Returned</span>';
                      } else {
                        echo '<span class="badge text-bg-warning">Not-Returned</span>';
                      }
                      ?>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <div class="tab-pane fade" id="recentuser-pane" role="tabpanel" aria-labelledby="recentuser-tab" tabindex="0">
            <table class="table">
              <thead class="table-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">User Name</th>
                  <th scope="col">Email</th>
                  <th scope="col">Phone</th>
                  <th scope="col">Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 1;
                foreach ($tabs['users'] as $us) {
                ?>
                  <tr>
                    <th><?php echo $i++; ?></th>
                    <td><?php echo htmlspecialchars($us['name']); ?></td>
                    <td><?php echo htmlspecialchars($us['email']); ?></td>
                    <td><?php echo htmlspecialchars($us['phone']); ?></td>
                    <td>
                      <span class="badge text-bg-success">Active</span>
                    </td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

          <div class="tab-pane fade" id="disabled-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0"></div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include_once($dir_url . "include/footer.php"); ?>