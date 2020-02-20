<?php

    require_once '../config/connect.php';
    header("Content-Type: application/vnd.ms-excel");
    header("Content-disposition: attachment; filename=download.xls");
    echo '
    <table border="1">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Category</th>
                    <th>Average Rating</th>
                </tr>
            </thead>
            <tbody>
    
  
            <tr>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
            <td>1</td>
                </tr>
  
  <tr>
      <td>1</td>
      <td>1</td>
      <td>1</td>
      <td>1</td>
  </tr>
  
  <tr>
      <td>1</td>
      <td>1</td>
      <td>1</td>
      <td>1</td>
  </tr>

            </tbody>
        </table>
';
    
?>
