<style>
    .highcharts-figure,
    .highcharts-data-table table {
        min-width: 360px;
        max-width: 1200px;
        margin: 1em auto;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #ebebeb;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }

    #container h4 {
        text-transform: none;
        font-size: 14px;
        font-weight: normal;
    }

    #container p {
        font-size: 13px;
        line-height: 16px;
    }

    @media screen and (max-width: 600px) {
        #container h4 {
            font-size: 2.3vw;
            line-height: 3vw;
        }

        #container p {
            font-size: 2.3vw;
            line-height: 3vw;
        }
    }
</style>
<figure class="highcharts-figure">
    <div id="container"></div>
    <p class="highcharts-description">
        Organization charts are a common case of hierarchical network charts,
        where the parent/child relationships between nodes are visualized.
        Highcharts includes a dedicated organization chart type that streamlines
        the process of creating these types of visualizations.
    </p>
</figure>
<script src="<?= base_url(); ?>assets/highcharts/highcharts.js?=v1.5"></script>
<script src="<?= base_url(); ?>assets/highcharts/sankey.js?=v1.5"></script>
<script src="<?= base_url(); ?>assets/highcharts/organization.js?=v1.5"></script>
<script src="<?= base_url(); ?>assets/highcharts/exporting.js?=v1.5"></script>
<script src="<?= base_url(); ?>assets/highcharts/accessibility.js?=v1.5"></script>
<script type="text/javascript">
    document.body.classList.add("sidebar-collapse");
    var submit,notif;
    // Highcharts.chart('container', {
    //     chart: {
    //         height: 600,
    //         inverted: true
    //     },

    //     title: {
    //         text: 'Highcharts Org Chart'
    //     },

    //     accessibility: {
    //         point: {
    //             descriptionFormat: '{add index 1}. {toNode.name}' +
    //                 '{#if (ne toNode.name toNode.id)}, {toNode.id}{/if}, ' +
    //                 'reports to {fromNode.id}'
    //         }
    //     },

    //     series: [{
    //         type: 'organization',
    //         name: 'Highsoft',
    //         keys: ['from', 'to'],
    //         data: [
    //             ['Shareholders', 'Board'],
    //             ['Board', 'CEO'],
    //             ['Board', 'CEO'],
    //             ['CEO', 'CTO'],
    //             ['CEO', 'CPO'],
    //             ['CEO', 'CSO'],
    //             ['CEO', 'HR'],
    //             ['CTO', 'Product'],
    //             ['CTO', 'Web'],
    //             ['CSO', 'Sales'],
    //             ['HR', 'Market'],
    //             ['CSO', 'Market'],
    //             ['HR', 'Market'],
    //             ['CTO', 'Market']
    //         ],
    //         levels: [{
    //             level: 0,
    //             color: 'silver',
    //             dataLabels: {
    //                 color: 'black'
    //             },
    //             height: 25
    //         }, {
    //             level: 1,
    //             color: 'silver',
    //             dataLabels: {
    //                 color: 'black'
    //             },
    //             height: 25
    //         }, {
    //             level: 2,
    //             color: '#980104'
    //         }, {
    //             level: 4,
    //             color: '#359154'
    //         }],
    //         nodes: [
    //             {
    //                 id: 'Shareholders',
    //                 title: 'Komisaris',
    //                 name: 'Eko Hendro Purnomo',
    //                 image: 'https://wp-assets.highcharts.com/www-highcharts-com/blog/wp-content/uploads/2022/06/30081411/portrett-sorthvitt.jpg'
    //             }, {
    //                 id: 'Board'
    //             }, {
    //                 id: 'CEO',
    //                 title: 'CEO',
    //                 name: 'Atle Sivertsen',
    //                 image: 'https://wp-assets.highcharts.com/www-highcharts-com/blog/wp-content/uploads/2022/06/30081411/portrett-sorthvitt.jpg'
    //             }, {
    //                 id: 'HR',
    //                 title: 'CFO',
    //                 name: 'Anne Jorunn Fjærestad',
    //                 color: '#007ad0',
    //                 image: 'https://wp-assets.highcharts.com/www-highcharts-com/blog/wp-content/uploads/2020/03/17131210/Highsoft_04045_.jpg'
    //             }, {
    //                 id: 'CTO',
    //                 title: 'CTO',
    //                 name: 'Christer Vasseng',
    //                 image: 'https://wp-assets.highcharts.com/www-highcharts-com/blog/wp-content/uploads/2020/03/17131120/Highsoft_04074_.jpg'
    //             }, {
    //                 id: 'CPO',
    //                 title: 'CPO',
    //                 name: 'Torstein Hønsi',
    //                 image: 'https://wp-assets.highcharts.com/www-highcharts-com/blog/wp-content/uploads/2020/03/17131213/Highsoft_03998_.jpg'
    //             }, {
    //                 id: 'CSO',
    //                 title: 'CSO',
    //                 name: 'Anita Nesse',
    //                 image: 'https://wp-assets.highcharts.com/www-highcharts-com/blog/wp-content/uploads/2020/03/17131156/Highsoft_03834_.jpg'
    //             }, {
    //                 id: 'Product',
    //                 name: 'Product developers'
    //             }, {
    //                 id: 'Web',
    //                 name: 'Web devs, sys admin'
    //             }, {
    //                 id: 'Sales',
    //                 name: 'Sales team'
    //             }, {
    //                 id: 'Market',
    //                 name: 'Marketing team',
    //                 column: 5
    //             }
    //         ],
    //         colorByPoint: false,
    //         color: '#007ad0',
    //         dataLabels: {
    //             color: 'white'
    //         },
    //         borderColor: 'white',
    //         nodeWidth: 'auto'
    //     }],
    //     tooltip: {
    //         outside: true
    //     },
    //     exporting: {
    //         allowHTML: true,
    //         sourceWidth: 800,
    //         sourceHeight: 600
    //     }
    // });
    Highcharts.chart('container', {
        chart: {
            height: 600,
            inverted: true
        },

        title: {
            text: 'Highcharts Org Chart'
        },

        accessibility: {
            point: {
                descriptionFormat: '{add index 1}. {toNode.name}' +
                    '{#if (ne toNode.name toNode.id)}, {toNode.id}{/if}, ' +
                    'reports to {fromNode.id}'
            }
        },

        series: [{
            type: 'organization',
            name: 'Highsoft',
            keys: ['from', 'to'],
            data: [
                ['CEO', 'CTO'],
                ['CEO', 'CPO'],
                ['CEO', 'CSO'],
                ['CEO', 'HR'],
                ['CTO', 'ETTK'],
                ['CPO', 'PH'],
                ['CSO', 'MA'],
                ['HR', 'KGE'],
                ['ETTK', 'GA'],
                ['ETTK', 'IT'],
                ['ETTK', 'HRD'],
                ['ETTK', 'FIN1'],
                ['PH', 'AKUNTAN'],
                ['PH', 'MANAGER1'],
                ['PH', 'PRODUSER'],
                ['MA', 'FIN2'],
                ['MA', 'MANAGER2'],
                ['KGE', 'FIN3'],
                ['KGE', 'OPERATION'],
                ['KGE', 'MARKETING'],
            ],
            levels: [{
                level: 0,
                color: '#980104',
                dataLabels: {
                    color: 'black'
                },
                height: 25
            }, {
                level: 1,
                color: '#359154',
                dataLabels: {
                    color: 'black'
                },
                height: 25
            }],
            nodeAlignment: 'center',
            nodes: [
                {
                    id: 'CEO',
                    title: 'Komisaris',
                    name: 'Eko Hendro Purnomo',
                    image: 'https://wp-assets.highcharts.com/www-highcharts-com/blog/wp-content/uploads/2022/06/30081411/portrett-sorthvitt.jpg',
                    dataLabels: [{
                        useHTML: false,
                        className: 'okelah',
                        enabled: false
                    }]
                },
                {
                    id: 'CTO',
                    title: 'Direktur',
                    name: 'Syawal',
                    image: 'https://wp-assets.highcharts.com/www-highcharts-com/blog/wp-content/uploads/2022/06/30081411/portrett-sorthvitt.jpg'
                },
                {
                    id: 'CPO',
                    title: 'Direktur',
                    name: 'Iqbal',
                    image: 'https://wp-assets.highcharts.com/www-highcharts-com/blog/wp-content/uploads/2022/06/30081411/portrett-sorthvitt.jpg'
                },
                {
                    id: 'CSO',
                    title: 'Direktur',
                    name: 'Wiwik',
                    image: 'https://wp-assets.highcharts.com/www-highcharts-com/blog/wp-content/uploads/2022/06/30081411/portrett-sorthvitt.jpg'
                },
                {
                    id: 'HR',
                    title: 'Direktur',
                    name: 'Puspa',
                    image: 'https://wp-assets.highcharts.com/www-highcharts-com/blog/wp-content/uploads/2022/06/30081411/portrett-sorthvitt.jpg'
                }
            ],
            colorByPoint: false,
            color: '#007ad0',
            dataLabels: {
                color: 'white'
            },
            borderColor: 'white',
            nodeWidth: 'auto'
        }],
        tooltip: {
            outside: true
        },
        exporting: {
            allowHTML: true,
            sourceWidth: 800,
            sourceHeight: 600
        }
    });
</script>