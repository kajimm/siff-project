<?php
namespace Core\Query;

class Select
{
    private $params;
    private $table;

    public function __construct(string $table, string...$params)
    {
        $this->table  = $table;
        $this->params = $params;
    }

    public function get()
    {
        $partes = ['SELECT'];
        if ($this->params) {
            $partes[] = join(', ', $this->params);
        } else {
            $partes[] = '*';
        }
        $partes[] = 'FROM ' . $this->table;
        return join(' ', $partes);
    }
}
