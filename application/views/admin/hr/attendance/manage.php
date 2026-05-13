<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php
$calendar_summary = array_merge([
    'present'  => 0,
    'absent'   => 0,
    'half_day' => 0,
    'week_off' => 0,
    'upcoming' => 0,
    'leave'    => 0,
], isset($calendar_data['summary']) ? $calendar_data['summary'] : []);

$today      = date('Y-m-d');
$prev_month = date('Y-m', strtotime($current_month . ' -1 month'));
$next_month = date('Y-m', strtotime($current_month . ' +1 month'));
?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <!-- <div class="col-md-12 mb-3">
                <h4 class="page-title">HR / Attendance</h4>
            </div> -->
        </div>
        <div class="panel_s">
            <div class="panel-body">
                  <div class="page-actions mb-4">
                    <div>
                        <h4 class="page-heading">HR / Attendance</h4>
                        <!-- <p class="page-subtitle">Track leave requests, presence, and attendance status at a glance with a clean calendar view.</p> -->
                    </div>
                </div>
                <!-- Summary Boxes -->
                <!-- <div class="summary-grid mb-4">
                    <div class="summary-box s-present">
                        <strong><?= $calendar_summary['present'] ?></strong>
                        <span>Present</span>
                    </div>
                    <div class="summary-box s-absent">
                        <strong><?= $calendar_summary['absent'] ?></strong>
                        <span>Absent</span>
                    </div>
                    <div class="summary-box s-halfday">
                        <strong><?= $calendar_summary['half_day'] ?></strong>
                        <span>Half Day</span>
                    </div>
                    <div class="summary-box s-leave">
                        <strong><?= $calendar_summary['leave'] ?></strong>
                        <span>Leave</span>
                    </div>
                    <div class="summary-box s-weekoff">
                        <strong><?= $calendar_summary['week_off'] ?></strong>
                        <span>Week Off</span>
                    </div>
                    <div class="summary-box s-upcoming">
                        <strong><?= $calendar_summary['upcoming'] ?></strong>
                        <span>Upcoming</span>
                    </div>
                </div> -->

                <!-- Calendar -->
                <div class="cal-wrap">
                    <div class="cal-header">
                        <a href="<?php echo admin_url('hr/attendance?month=' . $prev_month); ?>" class="cal-nav-btn">&lt;</a>
                        <span class="cal-title"><?php echo $month_name; ?></span>
                        <a href="<?php echo admin_url('hr/attendance?month=' . $next_month); ?>" class="cal-nav-btn">&gt;</a>
                    </div>

                    <?php
                    $days_of_week = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    $first_day    = date('w', strtotime($current_month . '-01'));
                    $total_days   = date('t', strtotime($current_month . '-01'));

                    $highlights = [];
                    foreach (isset($calendar_data['calendar']) ? $calendar_data['calendar'] : [] as $cv) {
                        $highlights[date('j', strtotime($cv['date']))] = [
                            'label'      => $cv['label'],
                            'punch_in'   => isset($cv['punch_in'])   ? $cv['punch_in']   : '',
                            'punch_out'  => isset($cv['punch_out'])  ? $cv['punch_out']  : '',
                            'work_hours' => isset($cv['work_hours']) ? $cv['work_hours'] : '',
                            'date'       => $cv['date'],
                            'status'     => $cv['status'],
                        ];
                    }
                    ?>

                    <div class="cal-grid">
                        <!-- Weekday headers -->
                        <?php foreach ($days_of_week as $i => $wd): ?>
                            <div class="cal-weekday <?= $i === 0 ? 'sun' : ($i === 6 ? 'sat' : '') ?>">
                                <?= $wd ?>
                            </div>
                        <?php endforeach; ?>

                        <!-- Empty offset -->
                        <?php for ($i = 0; $i < $first_day; $i++): ?>
                            <div class="cal-cell empty"></div>
                        <?php endfor; ?>

                        <!-- Day Cells -->
                        <?php for ($day = 1; $day <= $total_days; $day++):
                            $day_date = date('Y-m-d', strtotime($current_month . '-' . sprintf('%02d', $day)));
                            $h        = $highlights[$day] ?? null;
                            $status   = $h ? $h['status'] : '';
                            $is_today = ($day_date === $today);

                            $cell_cls = 'cal-cell cell-' . ($status ?: 'none');
                            if ($is_today) $cell_cls .= ' today-cell';

                            $num_cls = 'day-circle circle-' . ($status ?: 'none');
                        ?>
                            <div class="<?= $cell_cls ?>" onclick="showDayDetails('<?= $day_date ?>')">

                                <!-- Content left, circle top-right -->
                                <div class="cell-inner">
                                    <!-- Left: status text / time -->
                                          <div class="cell-left">
                                          <?php if ($h): ?>
                                                <?php if ($status === 'present' || $status === 'half_day'): ?>
                                                      <span class="cell-label lbl-present">
                                                      <?= $h['label'] ?>
                                                      </span>
                                                      <?php if (!empty($h['punch_in']) || !empty($h['punch_out'])): ?>
                                                      <span class="cell-time t-present">
                                                            <?= !empty($h['punch_in'])  ? $h['punch_in']  : '--:--' ?>
                                                            -
                                                            <?= !empty($h['punch_out']) ? $h['punch_out'] : '--:--' ?>
                                                      </span>
                                                      <?php endif; ?>
                                                      <?php if (!empty($h['work_hours'])): ?>
                                                      <span class="cell-time t-present" style="font-size:0.70rem; opacity:0.75;">
                                                            <?= $h['work_hours'] ?>
                                                      </span>
                                                      <?php endif; ?>
                                                <?php elseif ($status === 'absent'): ?>
                                                      <span class="cell-label lbl-absent">Absent</span>
                                                <?php elseif ($status === 'leave'): ?>
                                                      <span class="cell-label lbl-leave"><?= $h['label'] ?></span>
                                                <?php elseif ($status === 'week_off'): ?>
                                                      <span class="cell-label lbl-weekoff">Week Off</span>
                                                <?php elseif ($status === 'upcoming'): ?>
                                                      <span class="cell-label lbl-upcoming">Upcoming</span>
                                                <?php endif; ?>
                                          <?php endif; ?>
                                          </div>
                                    <!-- Right: day number circle -->
                                    <div class="<?= $num_cls ?>"><?= $day ?></div>
                                </div>

                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
* { box-sizing: border-box; }

/* ── Summary ── */
.summary-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 10px;
}
@media (max-width: 900px) { .summary-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 500px) { .summary-grid { grid-template-columns: repeat(2, 1fr); } }

.summary-box {
    background: #fff;
    border-radius: 16px;
    padding: 18px 14px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}
.summary-box strong { font-size: 2rem; font-weight: 700; line-height: 1; }
.summary-box span   { font-size: 0.80rem; font-weight: 500; color: #888; }
.s-present strong  { color: #27AE60; }
.s-absent strong   { color: #E74C3C; }
.s-halfday strong  { color: #F39C12; }
.s-leave strong    { color: #8E44AD; }
.s-weekoff strong  { color: #7F8C8D; }
.s-upcoming strong { color: #95A5A6; }

/* ── Calendar Wrap ── */
.cal-wrap {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

/* ── Header ── */
.cal-header {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 14px;
    padding: 16px 20px;
    border-bottom: 1px solid #e5e7eb;
}
.cal-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a1a2e;
}
.cal-nav-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #f3f4f6;
    color: #374151;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.9rem;
    transition: background 0.15s;
}
.cal-nav-btn:hover { background: #e0e0e0; }

/* ── Grid ── */
.cal-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    border-left: 1px solid #e5e7eb;
}

/* Weekday headers */
.cal-weekday {
    text-align: left;
    font-size: 0.82rem;
    font-weight: 600;
    color: #9CA3AF;
    padding: 10px 14px;
    border-right: 1px solid #e5e7eb;
    border-bottom: 1px solid #e5e7eb;
    background: #fafafa;
}
.cal-weekday.sun { color: #EF5350; }
.cal-weekday.sat { color: #42A5F5; }

/* ── Day Cell ── */
.cal-cell {
    min-height: 120px;
    border-right: 1px solid #e5e7eb;
    border-bottom: 1px solid #e5e7eb;
    padding: 12px;
    cursor: pointer;
    background: #fff;
    transition: background 0.12s;
}
.cal-cell:hover { filter: brightness(0.97); }
.cal-cell.empty {
    background: #fafafa !important;
    cursor: default;
}

/* Status backgrounds - image */
.cell-present  { background: #EEF9F4; }
.cell-half_day { background: #FFFBEC; }
.cell-absent   { background: #FEF0F0; }
.cell-leave    { background: #FEF9EC; }
.cell-week_off { background: #F5F5F5; }
.cell-upcoming { background: #FFFFFF; }
.cell-none     { background: #FFFFFF; }

/* Today */
.today-cell {
    outline: 2px solid #2C3E90;
    outline-offset: -2px;
}

/* ── Cell Inner Layout ── */
.cell-inner {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    height: 100%;
    min-height: 96px;
}

/* Left content */
.cell-left {
    display: flex;
    flex-direction: column;
    gap: 5px;
    flex: 1;
    padding-top: 2px;
}

/* Labels */
.cell-label {
    font-size: 0.78rem;
    font-weight: 600;
}
.lbl-present  { color: #1E8449; }
.lbl-absent   { color: #C0392B; }
.lbl-leave    { color: #7D6608; }
.lbl-weekoff  { color: #7F8C8D; }
.lbl-upcoming { color: #BDC3C7; }

/* Time text */
.cell-time {
    font-size: 0.75rem;
    font-weight: 500;
    line-height: 1.4;
}
.t-present { color: #1E8449; }

/* ── Day Circle (top-right) ── */
.day-circle {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.88rem;
    font-weight: 700;
    flex-shrink: 0;
}
.circle-present  { background: #27AE60; color: #fff; }
.circle-half_day { background: #F39C12; color: #fff; }
.circle-absent   { background: #E74C3C; color: #fff; }
.circle-leave    { background: #F5CBA7; color: #935116; }
.circle-week_off { background: #BDC3C7; color: #fff; }
.circle-upcoming { background: #ECF0F1; color: #BDC3C7; }
.circle-none     { background: #ECF0F1; color: #BDC3C7; }
</style>

<script>
function showDayDetails(date) {
    $.ajax({
        url: admin_url + 'dashboard/get_day_status/' + date,
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#display_date').text(response.data.display_date || date);
                $('#attendance_status').text(response.data.attendance_status || 'N/A');
                $('#punch_in_time').text(response.data.punch_in_time || '-');
                $('#punch_out_time').text(response.data.punch_out_time || '-');
                $('#total_work_minutes').text(response.data.total_work_minutes || '-');
            }
        }
    });
}
</script>

<?php init_tail(); ?>