<?php

/** @var $interviews DateTime[][][]|string[][][] */
/** @var $myname string */
/** @var $myuser string */
/** @var $showOldInterviews bool */
$this->layout('base', ['title' => __('Recruiter'), 'logoHref' => 'interviews.php?byrecruiter=true']);

$later = [];
$prevdate = null;
$currentFileName = basename(__FILE__);

// get today date
try {
	$dt = new DateTime('now', new DateTimeZone('Europe/Rome'));
} catch (Exception $exception) {
	echo $exception->getMessage();
	die();
}

$today = $dt->format('Y-m-d');
?>

<?=$this->fetch('adminnavbar', ['name' => $myname, 'user' => $myuser, 'currentFileName' => $currentFileName])?>
<div class="d-flex justify-content-between">
	<h1><?=__('Recruiter')?></h1>
	<div class="d-flex align-items-center">
		<form method="get" id="showOldInterviewsForm">
			<input type="hidden" name="byrecruiter" value="1">
			<div class="form-check">
				<input class="form-check-input" type="checkbox" name="showOldInterviews"
					   <?= $showOldInterviews ? 'checked' : '' ?>
					   id="showOldInterviews" onclick="document.getElementById('showOldInterviewsForm').submit()">

				<label class="form-check-label" for="showOldInterviews">
					<?= __('Mostra vecchie interviste') ?>
				</label>
			</div>
		</form>
	</div>
</div>

<div class="row">
<?php
foreach ($interviews as $interviewer => $ints) {
	echo '<div class="col-lg-6 mb-3">';
	echo '<h4>' . $this->e($interviewer) . '</h4><ul class="list-group list-group-flush">';
	$prevdate = null;
	foreach ($ints as $int) {
		$date = $int['when']->format('Y-m-d');
		$time = $int['when']->format('H:i');

		// check for show old interviews
		if (!$showOldInterviews && $date < $today) {
			break;
		}

		if ($int['status'] === null) {
			$statusClass = '';
		} else {
			if ($int['status']) {
				$statusClass = 'list-group-item-success';
			} else {
				$statusClass = 'list-group-item-danger';
			}
		}
		if ($date !== $prevdate) {
			$prevdate = $date;
			?>
			<li class="list-group-item list-group-item-secondary">
				<?= sprintf(__('Giorno %s (%s)'), $date, $this->fetch('day', ['day' => $int['when']->format('N')])) ?>
			</li>
			<?php
		}
		?>
		<li class="list-group-item d-flex justify-content-between align-items-center <?=$statusClass?>">
			<span><?=sprintf(__('<a href="interviews.php?id=%d">%s</a> (%s)'), $this->e($int['id']), $this->e($int['name']), $this->e($int['area']))?></span>
			<a class="badge badge-primary" href="/interviews.php?id=<?=$this->e($int['id'])?>&download"><?=$time?></a>
		</li>
		<?php
	}
	echo '</ul>';
	echo '</div>';
}
?>
</div>
