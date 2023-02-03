
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Сортировка таблицы</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
</head>
<body>
  <table id="testTable">
	<caption>Таблица размеров обуви</caption>
    <thead>
      <tr>
    <th>Россия</th>
    <th>Великобритания</th>
    <th>Европа</th>
    <th>Длина ступни, см</th>
      </tr>
    </thead>
    <tbody>
   <tr><td>345</td><td>3.5</td><td>36</td><td>23</td></tr>
   <tr><td>355</td><td>4</td><td>36.6</td><td>23.3</td></tr>
   <tr><td>360</td><td>4.5</td><td>37.3</td><td>23.5</td></tr>
   <tr><td>365</td><td>5</td><td>38</td><td>24</td></tr>
   <tr><td>370</td><td>5.5</td><td>38.6</td><td>24.5</td></tr>
   <tr><td>380</td><td>6</td><td>39.3</td><td>25</td></tr>
   <tr><td>385</td><td>6.5</td><td>40</td><td>25.5</td></tr>
   <tr><td>390</td><td>7</td><td>40.6</td><td>25.3</td></tr>
   <tr><td>400</td><td>7.5</td><td>41.3</td><td>26</td></tr>
   <tr><td>405</td><td>8</td><td>42</td><td>26.5</td></tr>
   <tr><td>410</td><td>8.5</td><td>42.6</td><td>27</td></tr>
   <tr><td>420</td><td>9</td><td>43.3</td><td>27.5</td></tr>
   <tr><td>430</td><td>9.5</td><td>44</td><td>28</td></tr>
   <tr><td>435</td><td>10</td><td>44.6</td><td>28.3</td></tr>
   <tr><td>440</td><td>10.5</td><td>45.3</td><td>28.3</td></tr>
   <tr><td>445</td><td>11</td><td>46</td><td>29</td></tr>
   <tr><td>450</td><td>11.5</td><td>46.6</td><td>29.3</td></tr>
   <tr><td>460</td><td>12</td><td>47.3</td><td>30</td></tr>
   <tr><td>465</td><td>12.5</td><td>48</td><td>30.5</td></tr>
   <tr><td>470</td><td>13</td><td>48.6</td><td>31</td></tr>
   <tr><td>480</td><td>13.5</td><td>49.3</td><td>31.5</td></tr>
    </tbody>
  </table>
  <script>$(function() {
    $("#testTable").tablesorter();
});</script>
</body>
</html>