<?php
$board = new Board();
print("\nInstruction: Marking example: 23 \n");
$board->start();

class Board
{
    public $n = 3;
    public $conditions = [];
    public $player_X = []; //1
    public $player_Y = []; //2
    public $cell = [];
    public $result = [];

    public function __construct()
    {
        $this->generateCells();
    }

    public function generateCells()
    {
        for ($r = 0; $r < $this->n; $r++) {
            for ($c = 0; $c < $this->n; $c++) {
                $this->cell[] = $r . $c;
                $this->result[] = $r . $c;
            }
        }
    }

    public function updateBoard($item, int $player = 1)
    {
        $index = array_search($item, $this->result);
        $mark = $player == 1 ?'XX':'YY';
        $this->result[$index] = $mark;
        foreach($this->result as $i => $r){
            if($i%$this->n == 0){
                print("\n");
            }
            print($r." ");
        }
        print("\n");
    }

    public function checkResult(array $player)
    {
        //check player_X
        sort($player);

        if (count($player) < 3) {
            return false;
        }

        $first = $player[0];
        if ($first == 11) {
            $ar = array_slice($player, 1);
            if (count($ar) < 3) {
                return false;
            }

            $first = $ar[0];
            if ($first != 20) {
                return false;
            }
        }

        $x = $player[1];

        if (!$direction = $this->checkDirection($first, $x, $player)) {
            return false;
        }

        for ($i = 1; $i < $this->n - 1; $i++) {
            if ($player[$i] + $direction != $player[$i + 1]) {
                return false;
            }
        }
        return true;
    }

    public function checkDirection(int $first, int $x, array $player): bool|int
    {
        if (count($player) < 3) {
            return false;
        }

        if ($first + 11 == $x) {
            print("\n Hint: Match Diagonally\n");
            return 11;
        } elseif ($first + 9 == $x) {
            print("\n Hint: Match Diagonally\n");
            return 9;
        } elseif ($first + 10 == $x) {
            print("\n Hint: Match Vertically\n");
            return 10;
        } elseif ($first + 1 == $x) {
            print("\n Hint: Match Horizontally\n");
            return 1;
        }

        $newSet = array_slice($player, 1);
        return $this->checkResult($newSet);
    }

    public function start()
    {
        $this->player_1();
        $this->player_2();
        $this->start();
    }

    public function check($player)
    {
        $n = $this->n;
        if (count($this->cell) < ($n*$n - 5)) {
            print("\nchecking result ... ... ... \n");

            if($player == 1){
                $playerMarks = $this->player_X;
            }
            if($player == 2){
                $playerMarks = $this->player_Y;
            }
            if ($this->checkResult($playerMarks)) {
                $this->output("player ".$player." won the game");
            }
        }
    }

    public function output($msg)
    {
        print("\n\n************************************\n****** ".$msg." *******\n************************************");
        exit();
    }

    public function generateRandomMark()
    {
        if(count($this->cell) == 0){
            $this->output(" This game is draw.  ");
        }

        $num = array_rand($this->cell, 1);
        $ar = str_split($this->cell[$num]);
        unset($this->cell[$num]);
        return $ar;
    }

    public function player_1()
    {
        print("\nPlayer 1:\n--------------");
//        $input = readline(); //r,c
//        $ar = str_split((string)$input);
        list($r, $c) = $this->generateRandomMark();
        $this->mark[] = [$r, $c];
//        $this->player_X[] = $ar;
        $this->player_X[] = $r . $c;

        $this->updateBoard($r.$c, 1);
        $this->check(1);
    }

    public function player_2()
    {
        print("\nPlayer 2:\n--------------");
        list($r, $c) = $this->generateRandomMark();
        $this->mark[] = [$r, $c];
        $this->player_Y[] = $r . $c;

        $this->updateBoard($r.$c, 2);
        $this->check(2);
    }

    //possible combination of matching
    public function getConditions()
    {
        $h = $v = $d = $temp = [];
        $n = 3;
        for ($r = 0; $r < $n; $r++) {
            for ($c = 0; $c < $n; $c++) {
                $h[$r][] = [$r, $c];
                $v[$c][] = [$r, $c];
                if ($c == $r || ($c == $n - 1 && $r == 0) || ($c == 0 && $n - 1 == $r)) {
                    $temp[$r][] = [$r, $c];
                }
            }
        }

        //diagonal
        $d[0][] = [$temp[0][0], $temp[1][0], $temp[2][1]]; //00,11,22
        $d[1][] = [$temp[0][1], $temp[1][0], $temp[2][0]]; //02,11,20

        $this->conditions = [
            "h" => $h,
            "v" => $v,
            "d" => $d
        ];
    }

}