<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="screen-options-area"></div>
    <div class="screen-options-btn">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="tw-w-5 tw-h-5 ltr:tw-mr-1 rtl:tw-ml-1">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>

        <?= _l('dashboard_options'); ?>
    </div>
    <div class="content">
        <div class="row">
            <?php $this->load->view('admin/includes/alerts'); ?>

            <?php hooks()->do_action('before_start_render_dashboard_content'); ?>

            <div class="clearfix"></div>
            <div class="col-md-4 mtop20">
                <?php // echo '<pre>'; print_r($calendar_view); echo '</pre>';?>
                <style>
                    .calendar {
                        background: #fff;
                        border-radius: 8px;
                        padding: 12px;
                        /* box-shadow: 0 4px 24px rgba(0,0,0,0.08); */
                        width: 100%;
                    }
                    
                    .grid {
                        display: grid;
                        grid-template-columns: repeat(7, 1fr);
                        gap: 6px;
                    }
                    
                    .header-cell {
                        text-align: center;
                        font-size: 13px;
                        font-weight: 600;
                        padding: 6px 0 10px;
                        color: #aab;
                    }
                    
                    .header-cell:first-child { color: #e05252; }
                    
                    .day-cell {
                        text-align: center;
                        padding: 8px 4px;
                        border-radius: 10px;
                        font-size: 14px;
                        font-weight: 500;
                        color: #ccc;
                        cursor: pointer;
                    }
                    
                    .day-cell.active {
                        font-weight: 600;
                    }
                </style>
                <?php
                $month = date('m'); // April
                $year = date('Y');
                $today = date('d');
                 
                // Day highlights: day => [bg_color, text_color, border]
                $statusColors = [
                    'attendance' => ['#d4f0e0', '#3aaa6a'],
                    'absent'  => ['#fde8e8', '#e05252'],
                    'request' => ['#ede0f7', '#9b59b6'],
                    'holiday' => ['#fef3cd', '#e09b2a'],
                    'upcoming'=> ['#f5f5f5', '#999999'],
                    'half_day'=> ['#fff4d6', '#d68910']
                ];
                $highlights = [];
                foreach ($calendar_view['calendar'] as $key=>$cv){
                    $highlights[date('j', strtotime($cv['date']))] = [
                        'bg'      => $statusColors[$cv['type']][0],
                        'color'   => $statusColors[$cv['type']][1],
                        'border'  => (($today == date('d', strtotime($cv['date']))) ? '2px solid '.$statusColors[$cv['type']][1] : ''),
                        'title'   => ucfirst(str_replace('_', ' ', $cv['status'] ?? $cv['type'])),
                        'onclick' => "showDayDetails('".$cv['date']."')",
                        'type'    => $cv['type'],
                        'date'    => $cv['date']
                    ];
                }
                 
                $days_of_week = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];
                $first_day = date('w', mktime(0, 0, 0, $month, 1, $year)); // 0=Sun
                $total_days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                ?>
                <div class="calendar">
                    <div class="grid">
                    
                        <?php foreach ($days_of_week as $d): ?>
                        <div class="header-cell"><?= $d ?></div>
                        <?php endforeach; ?>
                    
                        <?php
                        // Empty cells before first day
                        for ($i = 0; $i < $first_day; $i++):
                        ?>
                        <div class="day-cell"></div>
                        <?php endfor; ?>
                    
                        <?php for ($day = 1; $day <= $total_days; $day++):
                        $h = $highlights[$day] ?? null;
                        $bg     = $h ? $h['bg'] : 'transparent';
                        $color  = $h ? $h['color'] : '#ccc';
                        $border = ($h && $h['border']) ? "border: {$h['border']};" : '';
                        $cls    = $h ? 'day-cell active' : 'day-cell';
                        $style  = "background:{$bg}; color:{$color}; {$border}";
                        ?>
                        <div class="<?= $cls ?>" style="<?= $style ?>" title="<?= $h['title']?>" onclick="<?= $h['onclick']?>"><?= $day ?></div>
                        <?php endfor; ?>
                    
                    </div>
                </div>
            </div>
            <div class="col-md-4 mtop20">
                <style>
                    .att-date {
                        font-size: 16px;
                        font-weight: 700;
                        color: #1a1a2e;
                        margin-top: 5px;
                        margin-bottom: 30px;
                    }
                    
                    .att-row {
                        display: flex;
                        align-items: center;
                        padding: 13px 0;
                        border-bottom: 1px solid #f0f0f5;
                    }
                    .att-row:last-child { border-bottom: none; }
                    
                    .att-icon {
                        width: 36px;
                        height: 36px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin-right: 14px;
                        flex-shrink: 0;
                    }
                    .att-icon svg { width: 18px; height: 18px; }
                    
                    .icon-blue   { background: #e8f0fe; }
                    .icon-red    { background: #fde8e8; }
                    .icon-green  { background: #d4f0e0; }
                    .icon-purple { background: #ede0f7; }
                    
                    .att-label {
                        flex: 1;
                        font-size: 14px;
                        color: #6b7280;
                        font-weight: 500;
                    }
                    
                    .att-value {
                        font-size: 14px;
                        font-weight: 600;
                        color: #1a1a2e;
                    }
                    .att-value.green { color: #3aaa6a; }
                    
                    .badge {
                        background: #f0f4ff;
                        color: #4f6ef7;
                        font-size: 13px;
                        font-weight: 600;
                        padding: 4px 14px;
                        border-radius: 20px;
                    }
                </style>
                <div class="calendar">
                    <!-- <h5 style="font-weight: bold; border-bottom: 1px solid #eee; padding-bottom: 5px;"><?= date('D, d M Y');?></h6> -->
                    <div class="card">
                        <div class="att-date">
                            <span id="display_date"><?= date('D, d M Y');?></span>
                            <span id="attendance_status" class="icon-blue" style="float: right; padding: 5px 15px; border-radius: 10px; font-size: 10px;"><?= $today_attendance['attendance_status'];?></span>
                        </div>
                        <?php // echo '<pre>'; print_r($today_attendance); echo '</pre>';?>
                        <div class="att-row">
                        <div class="att-icon icon-blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#4f6ef7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <span class="att-label">Check In</span>
                        <span class="att-value" id="punch_in_time"><?= $today_attendance['punch_in_time'];?></span>
                        </div>
                    
                        <div class="att-row">
                        <div class="att-icon icon-red">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#e05252" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <span class="att-label">Check Out</span>
                        <span class="att-value" id="punch_out_time"><?= $today_attendance['punch_out_time'];?></span>
                        </div>
                    
                        <div class="att-row">
                        <div class="att-icon icon-green">
                            <svg viewBox="0 0 24 24" fill="none" stroke="#3aaa6a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <span class="att-label">Total Hours</span>
                        <span class="att-value green" id="total_work_minutes"><?= $today_attendance['total_work_minutes'];?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mtop20">
                <div class="calendar">
                    <div class="grid" style="grid-template-columns: repeat(3, 1fr);">
                        <div class="day-cell" style="color: #000; border: 2px solid #3aaa6a;"><span style="color: #3aaa6a; font-size: 18px;"><?= $calendar_view['summary']['present'];?></span><br>Present</div>
                        <div class="day-cell" style="color: #000; border: 2px solid #e05252;"><span style="color: #e05252; font-size: 18px;"><?= $calendar_view['summary']['absent'];?></span><br>Absent</div>
                        <div class="day-cell" style="color: #000; border: 2px solid #7faa3a;"><span style="color: #7faa3a; font-size: 18px;"><?= $calendar_view['summary']['half_day'];?></span><br>Half Day</div>
                        <div class="day-cell" style="color: #000; border: 2px solid #9b59b6;"><span style="color: #9b59b6; font-size: 18px;"><?= $calendar_view['summary']['leave'];?></span><br>Leave</div>
                        <div class="day-cell" style="color: #000; border: 2px solid #e09b2a;"><span style="color: #e09b2a; font-size: 18px;"><?= $calendar_view['summary']['week_off'];?></span><br>Week Off</div>
                        <div class="day-cell" style="color: #000; border: 2px solid #999999;"><span style="color: #999999; font-size: 18px;"><?= $calendar_view['summary']['upcoming'];?></span><br>Upcoming</div>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>

            <div class="col-md-12 mtop20" data-container="top-12">
                <?php render_dashboard_widgets('top-12'); ?>
            </div>

            <?php hooks()->do_action('after_dashboard_top_container'); ?>

            <div class="col-md-6" data-container="middle-left-6">
                <?php render_dashboard_widgets('middle-left-6'); ?>
            </div>
            <div class="col-md-6" data-container="middle-right-6">
                <?php render_dashboard_widgets('middle-right-6'); ?>
            </div>

            <?php hooks()->do_action('after_dashboard_half_container'); ?>

            <div class="col-md-8" data-container="left-8">
                <?php render_dashboard_widgets('left-8'); ?>
            </div>
            <div class="col-md-4" data-container="right-4">
                <?php render_dashboard_widgets('right-4'); ?>
            </div>

            <div class="clearfix"></div>

            <div class="col-md-4" data-container="bottom-left-4">
                <?php render_dashboard_widgets('bottom-left-4'); ?>
            </div>
            <div class="col-md-4" data-container="bottom-middle-4">
                <?php render_dashboard_widgets('bottom-middle-4'); ?>
            </div>
            <div class="col-md-4" data-container="bottom-right-4">
                <?php render_dashboard_widgets('bottom-right-4'); ?>
            </div>

            <?php hooks()->do_action('after_dashboard'); ?>
        </div>
    </div>
</div>
<script>
    app.calendarIDs = '<?= json_encode($google_ids_calendars); ?>';

    function showDayDetails(date) {
        $.ajax({
            url: admin_url + 'dashboard/get_day_status/' + date,
            dataType: 'json',
            success: function(response) {
                console.log(response);
                // Update the day details in the UI
                $('#display_date').text(response.data.display_date);
                $('#attendance_status').text(response.data.attendance_status);
                $('#punch_in_time').text(response.data.punch_in_time);
                $('#punch_out_time').text(response.data.punch_out_time);
                $('#total_work_minutes').text(response.data.total_work_minutes);
            }
        });
    }
</script>
<?php init_tail(); ?>
<?php $this->load->view('admin/utilities/calendar_template'); ?>
<?php $this->load->view('admin/dashboard/dashboard_js'); ?>
</body>

</html>