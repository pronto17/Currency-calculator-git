<?php
    $date = '2015-11-11';
    $bank_data = [
        'Eesti Pank',
        'Leedu Pank'
    ]
?>
<!DOCTYPE html>
<html lang="en">
<style>
    .styled-table {
        border-collapse: collapse;
        margin: 25px 0;
        font-size: 0.7em;
        font-family: sans-serif;
        min-width: 10em;
        text-align: center;
        border: 0.5px solid;
    }

    td {
        border: 0.5px solid;
        padding:0.3em;
    }
    th {
        padding: 0.4em;
    }
}
</style>
<body>
    <div></div>
    <div>
        <h3>Currency Calculator</h3>
        <input type="date" value="2015-11-11" id="date" onChange="displaycal(this.value)">
        <select name="banks" id="banks" onChange="displaycal(document.getElementById('date').value)">
            <?php foreach ($bank_data as $bank) echo("<option value='$bank'>$bank</option>"); ?>
        </select>
        <br>
        <input type="number" id="nummmber">
        <select name="currencies" id="currencies"></select>
        <input type="button" value="convert" id="convertBtn" onClick="cconvert()">
        <h3 id="converted"></h3>
        <table id="rateTable" class="styled-table">
            <tr>
                <th>Currency</th>
                <th>Rate</th>
            </tr>
        </table>
    </div>
</body>
</html>
<script>
    window.onload = (event) => {
        displaycal("2015-11-11")
    };

    async function displaycal(date) {
        chosen_bank = document.getElementById('banks').value;
        url = "";
        if (chosen_bank == 'Eesti Pank') {
            url = 'https://haldus.eestipank.ee/et/export/currency_rates?imported=' + date + '&type=json';
        }
        if (chosen_bank == 'Leedu Pank') {
            url = 'https://www.lb.lt/fxrates_csv.lb?tp=EU&rs=1&dte=' + date;
        }

        let options = {
            method: 'GET',
            headers: {
                'Content-Type':'application/json;charset=utf-8',
                'Access-Control-Allow-Origin': '*'
            }
        }

        const res = await fetch(url, options)
            .then(response => response.text());

        const result = res.split(/\r?\n/);
        let rates = [];

        if (chosen_bank == 'Eesti Pank') {
            for (let index = 3; index < result.length-1; index++) {
                line = result[index].split(',');
                rates[line[0]] = line[1];
            }
        }

        if (chosen_bank == 'Leedu Pank') {
            for (let index = 0; index < result.length-1; index++) {
                line = result[index].split(',');
                rates[line[1]] = line[2];
            }
        }


        document.getElementById('currencies').options.length = 0;
        for (var i = document.getElementById("rateTable").rows.length; i > 1; i--) {
            document.getElementById("rateTable").deleteRow(i - 1);
        } 

        for(rate in rates)
            {
            var opt = document.createElement("option");
            opt.value= rates[rate];
            opt.innerHTML = rate;
            
            document.getElementById('currencies').appendChild(opt);

            var tr = document.createElement('tr');
            tr.innerHTML = '<td>' + rate + '</td><td>' + rates[rate] + '</td>';
            document.getElementById('rateTable').children[0].appendChild(tr);
            }

    }

    function cconvert() {
        let euro = document.getElementById('nummmber').value;
        let currencyRate = document.getElementById('currencies').value;

        console.log(euro);

        if (isEmpty(euro)) {
            document.getElementById('converted').innerHTML = "input field empty"
        } else {
            let value_calc = euro * currencyRate;
            document.getElementById('converted').innerHTML = value_calc
        }

    }

    function isEmpty(str) {
        return (!str || str.length === 0 );
    }
</script>