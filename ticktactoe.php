<?php
$board = new Board();
$board->updateBoard();

class Board
{
    public $board = [];
    public $mark = [];
    public $n = 3;
    public $conditions = [];
    public $player_X = [];
    public $player_Y = [];
    public $cell = [];

    public function __construct()
    {
        $this->generateCells();
    }

    public function generateCells()
    {
        for ($r = 0; $r < $this->n; $r++) {
            for ($c = 0; $c < $this->n; $c++) {
                $this->cell[] = $r . $c;

            }
        }
    }

    public function updateBoard()
    {
        //neutralize
        $markCount = 0;

        $n = $this->n;
        for ($r = 0; $r < $n; $r++) {
            for ($c = 0; $c < $n; $c++) {
                if ($this->hasMark([$r, $c])) {
                    if (in_array($r . $c, $this->player_X)) {
                        print('XX ');
                    } else {
                        print('YY ');
                    }
                    $markCount++;
                } else {
                    print($r . "" . $c . " ");
                }
            }
            print("\n");
        }

        if ($markCount >= 5) {//5
            print("\nchecking result =======> \n");

            if ($this->checkResult($this->player_X)) {
                print("player X won the game");
                exit();
            }
            if ($this->checkResult($this->player_Y)) {
                print("player Y won the game");
                exit();
            }
            print("mark " . $markCount);
        }
        $this->mark();
    }

    public function hasMark(array $mark)
    {
        return in_array($mark, $this->mark);
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
            print("\n probability: Diagonal Matching\n");
            return 11;
        } elseif ($first + 9 == $x) {
            print("\n probability: Diagonal Matching\n");
            return 9;
        } elseif ($first + 10 == $x) {
            print("\n probability: Vertical Matching\n");
            return 10;
        } elseif ($first + 1 == $x) {
            print("\n probability: Horizontal Matching\n");
            return 1;
        }

        $newSet = array_slice($player, 1);
        return $this->checkResult($newSet);
    }

    public function mark()
    {
        $this->player_1();

        $this->player_2();
        $this->updateBoard();
    }

    public function generateRandomMark()
    {
        if(!count($this->cell)){
                print("The game is draw. Do you want to try again?");
                exit();
        }
        $num = array_rand($this->cell, 1);
        $ar = str_split($this->cell[$num]);
        unset($this->cell[$num]);
        return $ar;
    }

    public function player_1()
    {
        print("\neg: 23 -- Player 1 Turn Mark :");
        print("\n\n");
//        $input = readline(); //r,c
//        $ar = explode('', $input);
//        $ar = str_split((string)$input);
        list($r, $c) = $this->generateRandomMark();
        $ar = [$r, $c];
        $this->mark[] = $ar;
        $this->player_X[] = $r . $c;

    }
    public function player_2()
    {
        list($r, $c) = $this->generateRandomMark();
        $this->mark[] = [$r, $c];
        $this->player_Y[] = $r . $c;
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