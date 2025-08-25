<?php
$this->title = 'Test AJAX';
?>

<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h3>Test de AJAX</h3>
        </div>
        <div class="card-body">
            <button id="testBtn" class="btn btn-primary">Probar AJAX</button>
            <div id="result" class="mt-3"></div>
        </div>
    </div>
</div>

<?php
$this->registerJs("
document.getElementById('testBtn').addEventListener('click', function() {
    const resultDiv = document.getElementById('result');
    resultDiv.innerHTML = 'Probando...';
    
    fetch('" . \yii\helpers\Url::to(['site/test-ajax']) . "', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'test=1'
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.json();
    })
    .then(data => {
        console.log('Data received:', data);
        resultDiv.innerHTML = '<div class=\"alert alert-success\">AJAX funcionó! Mensaje: ' + data.message + '<br>Timestamp: ' + data.timestamp + '</div>';
    })
    .catch(error => {
        console.error('Error:', error);
        resultDiv.innerHTML = '<div class=\"alert alert-danger\">Error: ' + error.message + '</div>';
    });
});
");
?>
