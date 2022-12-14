<?php
function notification( $code, $user, $sender, $project, $job )
{
	switch ($code) {
    case 1:
		$us = R::findOne('user', ' id = ?', [ $sender ]);
		$pr = R::findOne('task', ' id = ?', [ $project ]);
        $tresc = "Zostałeś(aś) dodana do nowego projektu: ".$pr['nazwa']." przez: ".$us['login'];
        break;
    case 2:
        $us = R::findOne('user', ' id = ?', [ $sender ]);
		$pr = R::findOne('task', ' id = ?', [ $project ]);
		$jb = R::findOne('job', ' id = ?', [ $job ]);
        $tresc = "Zostało ci przydzielone nowe zadanie: ".$jb['nazwa']." w projekcie ".$pr['nazwa']." przez: ".$us['login'];
        break;
    case 3:
		$pr = R::findOne('task', ' id = ?', [ $project ]);
		$jb = R::findOne('job', ' id = ?', [ $job ]);
        $tresc = "Zbliża się termin oddania zadania: ".$jb['nazwa']." z projektu ".$pr['nazwa'];
        break;
	case 4:
		$us = R::findOne('user', ' id = ?', [ $sender ]);
        $pr = R::findOne('task', ' id = ?', [ $project ]);
		$jb = R::findOne('job', ' id = ?', [ $job ]);
        $tresc = $us['login']." ukończył zadanie ".$jb['nazwa']." z projektu ".$pr['nazwa'];
        break;
	case 5:
        $us = R::findOne('user', ' id = ?', [ $sender ]);
        $pr = R::findOne('task', ' id = ?', [ $project ]);
		$jb = R::findOne('job', ' id = ?', [ $job ]);
        $tresc = $us['login']." nie ukończył zadania ".$jb['nazwa']." z projektu ".$pr['nazwa']." w wyznaczonym terminie";
        break;
	case 6:
		$us = R::findOne('user', ' id = ?', [ $sender ]);
        $pr = R::findOne('task', ' id = ?', [ $project ]);
		$jb = R::findOne('job', ' id = ?', [ $job ]);
        $tresc = $us['login']." wprowadził poprawki w zadaniu ".$jb['nazwa']." z projektu ".$pr['nazwa'];
        break;
	case 7:
		$pr = R::findOne('task', ' id = ?', [ $project ]);
		$jb = R::findOne('job', ' id = ?', [ $job ]);
        $tresc = "Minął termin oddania zadania: ".$jb['nazwa']." z projektu ".$pr['nazwa'];
        break;
}
	
	$not = R::dispense('notification');
	$not->tresc=$tresc;
	$not->id_user=$user;
	$not->data=R::isoDateTime();
	$id = R::store( $not );
}
function checkLate()
{
	$curr = date("d-m-y h:i:s");
	
	$job = R::find('job', ' termin > ? ', [$curr] );
		
		foreach ($job as $jb)
		{
			$ass = R::find('assignment', ' id_job = ? AND status = 1', [$jb->id] );
			$pro = R::findOne('task', ' id = ?', [ $jb->task ]);
			foreach ($ass as $as)
			{
				notification( 5, $jb->tworca, $as->id_user, $pro['id'], $as->id_job );
				notification( 7, $as->id_user, NULL, $pro['id'], $as->id_job );
			}
		}
}

function checkSoon()
{
	$curr = date("d-m-y h:i:s");
	$date = date("d-m-y h:i:s", strtotime('+1 days'));
	
	$job = R::find('job', ' termin > ? AND termin < ?', [$date,$curr] );
		
		foreach ($job as $jb)
		{
			$ass = R::find('assignment', ' id_job = ? AND status = 1', [$jb->id] );
			$pro = R::findOne('task', ' id = ?', [ $jb->task ]);
			foreach ($ass as $as)
			{
				notification( 3, $as->id_user, NULL, $pro['id'], $as->id_job );
			}
		}
}

function notify()
{
	require 'rb-mysql.php';
	R::setup( 'mysql:host=localhost;dbname=tasks','root', '' );
	checkLate();
	checkSoon();
}

//notify();
?>

























