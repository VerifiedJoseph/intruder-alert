<?php

class Plots
{
    public function last24Hours(array $addresses): array
    {
        $data = [
            'labels' => [],
            'data' => []
        ];

        $temp = [];
        foreach ($addresses as $item) {
            foreach ($item['events'] as $event) {
                $date = new DateTime($event['timestamp']);
                $hour = $date->format('Y-m-d H:00');

                $key = array_search($hour, array_column($temp, 'date'));

                if ($key === false) {
                    $temp[] = [
                        'count' => 1,
                        'date' => $hour
                    ];
                } else {
                    $temp[$key]['count']++;
                }
            }
        }

        $temp = $this->sortByDate($temp);

        $date = new DateTime();
        $date->modify('-1 day');
        $yesterday = $date->format('Y-m-d H:00');

        foreach ($temp as $item) {
            if (strtotime($item['date']) >= strtotime($yesterday)) {
                $data['labels'][] = $item['date'];
                $data['data'][] = $item['count'];
            }
        }

        return $data;
    }

    public function lastXDays(array $addresses, int $days): array
    {
        $data = [
            'labels' => [],
            'data' => []
        ];

        $temp = [];
        foreach ($addresses as $item) {
            foreach ($item['events'] as $event) {
                $date = new DateTime($event['timestamp']);
                $hour = $date->format('Y-m-d');

                $key = array_search($hour, array_column($temp, 'date'));

                if ($key === false) {
                    $temp[] = [
                        'count' => 1,
                        'date' => $hour
                    ];
                } else {
                    $temp[$key]['count']++;
                }
            }
        }

        $temp = $this->sortByDate($temp);

        $date = new DateTime();
        $date->modify(sprintf('-%s day', $days));
        $yesterday = $date->format('Y-m-d');

        foreach ($temp as $item) {
            if (strtotime($item['date']) >= strtotime($yesterday)) {
                $data['labels'][] = $item['date'];
                $data['data'][] = $item['count'];
            }
        }

        return $data;
    }

    private function sortByDate(array $array): array
    {
        $ord = array();
        foreach ($array as $key => $value) {
            $ord[] = strtotime($value['date']);
        }

        array_multisort($ord, SORT_ASC, $array);

        return $array;
    }
}
