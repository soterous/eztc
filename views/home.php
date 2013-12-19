<?php


require_once('header.php');
?>  
    <div class="container">
      <div class="panel panel-info">
        <div class="panel-heading">
          <h3 class="panel-title">Most Recently Updated Project Strings <span>(These are links)</span></h3>
        </div>

        <table class="table table-hover recentprojects">
          <thead>
            <tr>
              <th>#</th>
              <th>Project String</th>
              <th>Total Hours</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><a href="details.php?p=123456789" class="disabled">1</a></td>
              <td>123456789</td>
              <td>20</td>
            </tr>
            <tr>
              <td><a href="details.php?p=ABCDEFG" class="disabled">2</a></td>
              <td>ABCDEFG</td>
              <td>76</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>  

<?php require_once('footer.php'); ?>