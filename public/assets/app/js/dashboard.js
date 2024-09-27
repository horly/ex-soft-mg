function changeDeviseDashboard()
{
    var token = $('#devise-global').attr('token');
    var url = $('#devise-global').attr('url');
    var id_devise = $('#devise-global').val();

    $.ajax({
        type : 'post',
        url : url,
        data : {
            '_token' : token,
            'id_devise' : id_devise,
        },
        success:function(response){
            //
            //console.log(response);
            //$('#recettes-global').text(response.recettes);
            //$('#depenses-global').text(response.depenses);

            location.reload(true);
        }
    });
}

chartTotal();

function chartTotal()
{
    var id_deviseGl = $('#chart-evolution-income').attr('id_devise');
    var year_sel = $('#chart-evolution-income').attr('year');
    var receipes = $('#chart-evolution-income').attr('recipes');
    var expensesch = $('#chart-evolution-income').attr('expenses');
    var results = $('#chart-evolution-income').attr('results');
    var iscode = $('#chart-evolution-income').attr('iscode');


    var options = {
        series: [{
            name: receipes + " " + iscode,
            data: [getrecette(id_deviseGl, '00', year_sel, 'false'),]
        }, {
            name: expensesch + " " + iscode,
            data: [getdepense(id_deviseGl, '00', year_sel, 'false')]
        }, {
            name: results + " " + iscode,
            data: [getresultat(id_deviseGl, '00', year_sel, 'false')]
        }],
        colors : [
            '#26d4a8', '#ff5959', '#435ebe'
        ],
            chart: {
            type: 'bar',
            height: 200
        },
        legend: {
            show: false,
        },
        plotOptions: {
            bar: {
            horizontal: true,
            dataLabels: {
                position: 'top',
                },
            }
        },
        dataLabels: {
            enabled: true,
            offsetX: -6,
            style: {
            fontSize: '12px',
            colors: ['#000000']
            }
        },
        stroke: {
            show: true,
            width: 1,
        },
        tooltip: {
            shared: true,
            intersect: false
        },
        xaxis: {
            categories: [year_sel],
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart-total-year"), options);
    chart.render();
}


// Exemple d'utilisation
setChart();

function setChart()
{

    var id_deviseGl = $('#chart-evolution-income').attr('id_devise');
    var year_sel = $('#chart-evolution-income').attr('year');
    var receipes = $('#chart-evolution-income').attr('recipes');
    var expensesch = $('#chart-evolution-income').attr('expenses');
    var results = $('#chart-evolution-income').attr('results');
    var iscode = $('#chart-evolution-income').attr('iscode');

    var optionsProfileVisit = {
        series: [{
                name: receipes + " " + iscode,
                  data: [
                    getrecette(id_deviseGl, '01', year_sel, 'true'),
                    getrecette(id_deviseGl, '02', year_sel, 'true'),
                    getrecette(id_deviseGl, '03', year_sel, 'true'),
                    getrecette(id_deviseGl, '04', year_sel, 'true'),
                    getrecette(id_deviseGl, '05', year_sel, 'true'),
                    getrecette(id_deviseGl, '06', year_sel, 'true'),
                    getrecette(id_deviseGl, '07', year_sel, 'true'),
                    getrecette(id_deviseGl, '08', year_sel, 'true'),
                    getrecette(id_deviseGl, '09', year_sel, 'true'),
                    getrecette(id_deviseGl, '10', year_sel, 'true'),
                    getrecette(id_deviseGl, '11', year_sel, 'true'),
                    getrecette(id_deviseGl, '12', year_sel, 'true'),]
            }, {
                name: expensesch + " " + iscode,
                  data: [
                    getdepense(id_deviseGl, '01', year_sel, 'true'),
                    getdepense(id_deviseGl, '02', year_sel, 'true'),
                    getdepense(id_deviseGl, '03', year_sel, 'true'),
                    getdepense(id_deviseGl, '04', year_sel, 'true'),
                    getdepense(id_deviseGl, '05', year_sel, 'true'),
                    getdepense(id_deviseGl, '06', year_sel, 'true'),
                    getdepense(id_deviseGl, '07', year_sel, 'true'),
                    getdepense(id_deviseGl, '08', year_sel, 'true'),
                    getdepense(id_deviseGl, '09', year_sel, 'true'),
                    getdepense(id_deviseGl, '10', year_sel, 'true'),
                    getdepense(id_deviseGl, '11', year_sel, 'true'),
                    getdepense(id_deviseGl, '12', year_sel, 'true'),]
            }, {
                name: results + " " + iscode,
                  data: [
                    getresultat(id_deviseGl, '01', year_sel, 'true'),
                    getresultat(id_deviseGl, '02', year_sel, 'true'),
                    getresultat(id_deviseGl, '03', year_sel, 'true'),
                    getresultat(id_deviseGl, '04', year_sel, 'true'),
                    getresultat(id_deviseGl, '05', year_sel, 'true'),
                    getresultat(id_deviseGl, '06', year_sel, 'true'),
                    getresultat(id_deviseGl, '07', year_sel, 'true'),
                    getresultat(id_deviseGl, '08', year_sel, 'true'),
                    getresultat(id_deviseGl, '09', year_sel, 'true'),
                    getresultat(id_deviseGl, '10', year_sel, 'true'),
                    getresultat(id_deviseGl, '11', year_sel, 'true'),
                    getresultat(id_deviseGl, '12', year_sel, 'true'),]
            }
        ],
        colors : [
            '#26d4a8', '#ff5959', '#435ebe'
        ],
        chart: {
        type: 'bar',
        height: 430
        },
        plotOptions: {
            bar: {
            horizontal: false,
            dataLabels: {
                position: 'back',
            },
            }
        },
        dataLabels: {
            enabled: true,
            offsetX: -6,
            style: {
            fontSize: '12px',
            colors: ['#000000']
            }
        },
        stroke: {
            show: true,
            width: 1,
            colors: ['#fff']
        },
        tooltip: {
            shared: true,
            intersect: false
        },
        xaxis: {
            categories: ["Jan","Feb","Mar","Apr","May","Jun","Jul", "Aug","Sep","Oct","Nov","Dec"],
        },
    }


    var chartProfileVisit = new ApexCharts(document.querySelector("#chart-evolution-income"), optionsProfileVisit);
    chartProfileVisit.render();
}

function getrecette(id_devise, month, year, monthly)
{
    var id_fu = $('#chart-evolution-income').attr('id_fu');
    var url = $('#chart-evolution-income').attr('url');
    var token = $('#chart-evolution-income').attr('token');


    var rep = null;
    $.ajax({
        type : 'post',
        url : url,
        data : {
            '_token' : token,
            'id_fu' : id_fu,
            'id_devise' : id_devise,
            'month' : month,
            'year' : year,
            'monthly' : monthly,
        },
        success:function(response)
        {
            rep = response;
        },
        async : false
    });

    return rep.recettes;
}

function getdepense(id_devise, month, year, monthly)
{
    var id_fu = $('#chart-evolution-income').attr('id_fu');
    var url = $('#chart-evolution-income').attr('url');
    var token = $('#chart-evolution-income').attr('token');


    var rep = null;
    $.ajax({
        type : 'post',
        url : url,
        data : {
            '_token' : token,
            'id_fu' : id_fu,
            'id_devise' : id_devise,
            'month' : month,
            'year' : year,
            'monthly' : monthly,
        },
        success:function(response)
        {
            rep = response;
        },
        async : false
    });

    return rep.depenses;
}

function getresultat(id_devise, month, year, monthly)
{
    var id_fu = $('#chart-evolution-income').attr('id_fu');
    var url = $('#chart-evolution-income').attr('url');
    var token = $('#chart-evolution-income').attr('token');


    var rep = null;
    $.ajax({
        type : 'post',
        url : url,
        data : {
            '_token' : token,
            'id_fu' : id_fu,
            'id_devise' : id_devise,
            'month' : month,
            'year' : year,
            'monthly' : monthly,
        },
        success:function(response)
        {
            rep = response;
        },
        async : false
    });

    return rep.resultats;
}
