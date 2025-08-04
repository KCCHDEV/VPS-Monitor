var mainchart, gauge;
var refresh_time = 3;
var prev_total = prev_idle = 0;
var isLoading = false;

$(document).ready(function() {
    $("#refresh_time").text(refresh_time);
    $("#refresh_time_text").text(refresh_time);
    
    // Initialize charts with dark theme
    initializeCharts();
    
    // Add loading state
    showLoadingState();
    
    // Start refresh cycle
    setInterval(refresh, refresh_time * 1000); 
    
    // Initial data load
    refresh();
});

function initializeCharts() {
    // Set dark theme for Highcharts
    Highcharts.setOptions({
        global: { useUTC: false },
        chart: {
            backgroundColor: '#1a1f29',
            style: {
                fontFamily: 'Inter, sans-serif'
            }
        },
        title: {
            style: {
                color: '#ffffff',
                fontSize: '18px',
                fontWeight: '600'
            }
        },
        subtitle: {
            style: {
                color: '#94a3b8'
            }
        },
        xAxis: {
            gridLineColor: '#334155',
            lineColor: '#334155',
            tickColor: '#334155',
            labels: {
                style: {
                    color: '#94a3b8'
                }
            },
            title: {
                style: {
                    color: '#94a3b8'
                }
            }
        },
        yAxis: {
            gridLineColor: '#334155',
            lineColor: '#334155',
            tickColor: '#334155',
            labels: {
                style: {
                    color: '#94a3b8'
                }
            },
            title: {
                style: {
                    color: '#94a3b8'
                }
            }
        },
        tooltip: {
            backgroundColor: '#252b36',
            borderColor: '#334155',
            style: {
                color: '#ffffff'
            }
        },
        legend: {
            itemStyle: {
                color: '#94a3b8'
            },
            itemHoverStyle: {
                color: '#ffffff'
            }
        },
        colors: ['#06b6d4', '#8b5cf6', '#ef4444', '#10b981', '#f59e0b']
    });

    // Initialize main chart
    mainchart = new Highcharts.stockChart({
        rangeSelector: {
            buttons: [{
                count: 1,
                type: 'minute',
                text: '1M'
            }, {
                count: 5,
                type: 'minute',
                text: '5M'
            }, {
                count: 10,
                type: 'minute',
                text: '10M'
            }, {
                count: 15,
                type: 'minute',
                text: '15M'
            }, {
                type: 'all',
                text: 'All'
            }],
            inputEnabled: false,
            selected: 0,
            buttonTheme: {
                fill: '#252b36',
                stroke: '#334155',
                'stroke-width': 1,
                style: {
                    color: '#94a3b8'
                },
                states: {
                    hover: {
                        fill: '#334155',
                        style: {
                            color: '#ffffff'
                        }
                    },
                    select: {
                        fill: '#06b6d4',
                        style: {
                            color: '#ffffff'
                        }
                    }
                }
            }
        },
        tooltip: { 
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y:.1f}%</b><br/>',
            backgroundColor: '#252b36',
            borderColor: '#334155',
            borderRadius: 8,
            style: {
                color: '#ffffff'
            }
        },
        chart: { 
            renderTo: 'mainchart',
            backgroundColor: 'transparent',
            height: 400
        },
        title: { 
            text: '',
            style: {
                color: '#ffffff'
            }
        },
        xAxis: { 
            title: {
                text: "Time",
                style: {
                    color: '#94a3b8'
                }
            },
            type: "datetime"
        },
        yAxis: {
            title: {
                text: 'Usage (%)',
                style: {
                    color: '#94a3b8'
                }
            },
            max: 100,
            min: 0,
            tickInterval: 20,
            plotLines: [{
                color: '#ef4444',
                width: 1,
                value: 80,
                dashStyle: 'dash',
                label: {
                    text: 'High Usage',
                    style: {
                        color: '#ef4444'
                    }
                }
            }]
        },
        plotOptions: {
            series: {
                lineWidth: 2,
                marker: {
                    enabled: false,
                    states: {
                        hover: {
                            enabled: true,
                            radius: 5
                        }
                    }
                }
            }
        },
        series: [
            { name: 'Memory', data: getDummyData(), color: '#06b6d4' },
            { name: 'Storage', data: getDummyData(), color: '#8b5cf6' },
            { name: 'CPU', data: getDummyData(), color: '#ef4444' }
        ]
    });

    // Initialize gauge chart
    gauge = Highcharts.chart('gaugeUsage', {
        chart: {
            type: 'solidgauge',
            backgroundColor: 'transparent',
            height: 250
        },
        title: {
            text: 'System Load',
            style: {
                fontSize: '16px',
                fontWeight: '600',
                color: '#ffffff'
            }
        },
        tooltip: {
            borderWidth: 0,
            backgroundColor: '#252b36',
            borderRadius: 8,
            shadow: false,
            style: {
                fontSize: '14px',
                color: '#ffffff'
            },
            valueSuffix: '%',
            pointFormat: '<span style="color:{point.color}">{series.name}</span><br><span style="font-size:1.5em; font-weight: bold">{point.y:.1f}%</span>',
            positioner: function (labelWidth) {
                return {
                    x: (this.chart.chartWidth - labelWidth) / 2,
                    y: (this.chart.plotHeight / 2) + 15
                };
            }
        },
        pane: {
            startAngle: 0,
            endAngle: 360,
            background: [{
                outerRadius: '112%',
                innerRadius: '88%',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                borderWidth: 0
            }, {
                outerRadius: '87%',
                innerRadius: '63%',
                backgroundColor: 'rgba(6, 182, 212, 0.1)',
                borderWidth: 0
            }, {
                outerRadius: '62%',
                innerRadius: '38%',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                borderWidth: 0
            }]
        },
        yAxis: {
            min: 0,
            max: 100,
            lineWidth: 0,
            tickPositions: []
        },
        plotOptions: {
            solidgauge: {
                dataLabels: {
                    enabled: false
                },
                rounded: true
            }
        },
        series: [{
            name: 'CPU',
            data: [{
                color: '#ef4444',
                radius: '112%',
                innerRadius: '88%',
                y: 0
            }]
        }, {
            name: 'Memory',
            data: [{
                color: '#06b6d4',
                radius: '87%',
                innerRadius: '63%',
                y: 0
            }]
        }, {
            name: 'Storage',
            data: [{
                color: '#8b5cf6',
                radius: '62%',
                innerRadius: '38%',
                y: 0
            }]
        }]
    });
}

function getDummyData() {
    var chartdata = new Array(), curtime = new Date().getTime();
    for (i = -399; i <= 0; i++) {
        chartdata.push([curtime + i * 1000, 0]);
    }
    chartdata.push([curtime, 0]);
    return chartdata;
}

function showLoadingState() {
    $("#general_info").html('<i class="fas fa-spinner fa-spin me-2"></i>Loading system information...');
}

function showErrorState(message) {
    $("#general_info").html(`<i class="fas fa-exclamation-triangle me-2 text-warning"></i>${message}`);
}

function refresh() {
    if (isLoading) return;
    
    isLoading = true;
    
    $.getJSON("getData.php", null, function(data) {
        try {
            var time = (new Date()).getTime();

            var cpuload = getCpuLoad(data.CPUDetail);
            var currentram = ((data.memory[1] / data.memory[0]) * 100).toFixed(2);
            var currenthdd = ((data.storage["used"] / data.storage["total"]) * 100).toFixed(2);
            var currentcpu = cpuload > 100 ? 100 : cpuload;

            // Update charts
            mainchart.series[0].addPoint([time, parseFloat(currentram)], false, true);
            mainchart.series[1].addPoint([time, parseFloat(currenthdd)], false, true);
            mainchart.series[2].addPoint([time, parseFloat(currentcpu)], true, true);

            // Update memory metrics
            updateMetricCard('ram', {
                usage: formatNumber(data.memory[1]) + " GB",
                total: formatNumber(data.memory[0]) + " GB",
                free: formatNumber(data.memory[2]) + " GB",
                percentage: currentram,
                cache: formatNumber(data.memory[3]) + " GB"
            });

            // Update storage metrics
            updateMetricCard('hdd', {
                usage: formatNumber(data.storage["used"]) + " GB",
                total: formatNumber(data.storage["total"]) + " GB",
                free: formatNumber(data.storage["free"]) + " GB",
                percentage: currenthdd
            });

            // Update network metrics
            $("#network .rec").html(`
                <div>${formatBytes(data.network[0])}</div>
                <small class="text-muted">Packets: ${formatNumber(data.network[1])}</small>
            `);
            $("#network .sent").html(`
                <div>${formatBytes(data.network[2])}</div>
                <small class="text-muted">Packets: ${formatNumber(data.network[3])}</small>
            `);

            // Update system info
            var info = `
                <i class="fas fa-clock me-2"></i>
                Uptime: ${getTime(data.uptime)} | 
                <i class="fas fa-desktop me-2"></i>
                OS: ${data.OS}
            `;
            $("#general_info").html(info);

            // Update CPU information
            updateCpuInfo(data.CPU);

            // Update gauges with animation
            animateGaugeUpdate(0, parseFloat(currentcpu));
            animateGaugeUpdate(1, parseFloat(currentram));
            animateGaugeUpdate(2, parseFloat(currenthdd));

        } catch (error) {
            console.error('Error processing data:', error);
            showErrorState('Error processing system data');
        }
    }).fail(function(xhr, status, error) {
        console.error('Failed to fetch data:', error);
        showErrorState('Failed to connect to server');
    }).always(function() {
        isLoading = false;
    });
}

function updateMetricCard(cardId, metrics) {
    const card = $(`#${cardId}`);
    
    // Update usage with cache info for RAM
    if (cardId === 'ram' && metrics.cache) {
        card.find('.usage').html(`${metrics.usage}<br/><small class="text-muted">Cache: ${metrics.cache}</small>`);
    } else {
        card.find('.usage').text(metrics.usage);
    }
    
    card.find('.total').text(metrics.total);
    card.find('.free').text(metrics.free);
    
    // Update usage bar with animation
    const usageBar = $(`#${cardId}-usage-bar`);
    const percentage = Math.min(parseFloat(metrics.percentage), 100);
    
    // Animate the usage bar
    usageBar.css('width', percentage + '%');
    
    // Change color based on usage level
    usageBar.removeClass('bg-success bg-warning bg-danger');
    if (percentage >= 90) {
        usageBar.css('background', 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)');
    } else if (percentage >= 70) {
        usageBar.css('background', 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)');
    } else {
        usageBar.css('background', 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)');
    }
}

function updateCpuInfo(cpuData) {
    const container = $(".cpu-cores-container");
    container.empty();
    
    for (var i = 0; i < cpuData.length; i += 3) {
        if (i + 2 < cpuData.length) {
            const coreDiv = $(`
                <div class="cpu-core">
                    <div class="cpu-core-name">${cpuData[i][1]}</div>
                    <div class="text-muted small">
                        <i class="fas fa-microchip me-1"></i>${cpuData[i + 1][0]}: ${cpuData[i + 1][1]}
                    </div>
                    <div class="text-muted small">
                        <i class="fas fa-tachometer-alt me-1"></i>${cpuData[i + 2][0]}: ${cpuData[i + 2][1]}
                    </div>
                </div>
            `);
            container.append(coreDiv);
        }
    }
}

function animateGaugeUpdate(seriesIndex, value) {
    if (gauge && gauge.series[seriesIndex]) {
        gauge.series[seriesIndex].points[0].update(value, true, {
            duration: 1000,
            easing: 'easeOutBounce'
        });
    }
}

function getCpuLoad(input) {
    var cpuload = input.split(' ');
    var sum = 0;

    for (var i = 0; i < cpuload.length; i++) {
        sum += parseInt(cpuload[i]);    
    }

    var idlecpuload = cpuload[3];
    var diff_idle = idlecpuload - prev_idle;
    var diff_total = sum - prev_total;
    var diff_usage = (1000 * (diff_total - diff_idle) / diff_total + 5) / 10;

    prev_total = sum;
    prev_idle = idlecpuload;
    
    return Math.max(0, diff_usage.toFixed(2));
}

function formatNumber(number) {
    return parseFloat(number).toLocaleString("en-US", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 2
    });
}

function formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function getTime(seconds) {
    var leftover = seconds;

    var days = Math.floor(leftover / 86400);
    leftover = leftover - (days * 86400);

    var hours = Math.floor(leftover / 3600);
    leftover = leftover - (hours * 3600);

    var minutes = Math.floor(leftover / 60);
    leftover = leftover - (minutes * 60);

    return `${days}d ${hours}h ${minutes}m ${leftover}s`;
}

// Add some utility functions for better UX
$(document).ready(function() {
    // Add smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 1000);
        }
    });
    
    // Add fade-in animation to cards
    $('.modern-card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
    });
    
    // Add tooltip functionality for better UX
    $('[data-bs-toggle="tooltip"]').tooltip();
});