<?php
namespace Core\Query;

/**
 *
 */
class QueryBuilder
{
    private $where;

    private $select;

    private $delete;

    private $insert;

    private $into;

    private $values;

    private $from;

    private $update;

    private $set;

    private $limit;

    private $order;

    private $like;

    /**
     * [from establece la tabla que se consultara]
     * @param  [type] $table [identificador de tabla]
     * @return [self]
     */
    public function from(string...$table): self
    {
        $this->from = $table;
        return $this;
    }

    public function select(string...$campos): self
    {
        $this->select = $campos;
        return $this;
    }

    public function insert(string $table)
    {
        $this->insert = $table;
        return $this;
    }

    public function into(string...$campos)
    {
        $this->into = $campos;
        return $this;
    }

    public function values(string...$values)
    {
        $this->values = $values;
        return $this;
    }

    public function delete(string $table)
    {
        $this->delete = $table;
        return $this;
    }

    public function update(string...$table)
    {
        $this->update = $table;
        return $this;
    }

    public function set(string...$values)
    {
        $this->set = $values;
        return $this;
    }

    public function where(string...$condition): self
    {
        $this->where = $condition;
        return $this;
    }

    public function limit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function orderBy(string...$condition)
    {
        $this->order = $condition;
        return $this;
    }

    public function __toString()
    {

        if ($this->delete) {

            $partes   = ['DELETE FROM'];
            $partes[] = $this->delete;

        } else if ($this->insert) {

            $partes   = ['INSERT INTO'];
            $partes[] = $this->insert;
            $partes[] = "(" . join(', ', $this->into) . ")";
            $partes[] = "VALUES (" . join(', ', $this->values) . ")";

        } else if ($this->update) {

            $partes   = ['UPDATE'];
            $partes[] = join(', ', $this->update);
            $partes[] = "SET " . join(', ', $this->set);

        } else {

            $partes = ['SELECT'];
            if ($this->select) {
                $partes[] = join(', ', $this->select);
            } else {
                $partes[] = "*";
            }
            $partes[] = "FROM";
            $partes[] = join(' AS ', $this->from);

        }

        if ($this->where) {
            $partes[] = 'WHERE';
            $partes[] = "(" . join(") AND (", $this->where) . ")";
        }

        if ($this->order) {

            $partes[] = 'ORDER BY';
            $cadena   = join(", ", $this->order);
            $parte    = $this->replace(",", '', $cadena);
            $partes[] = $parte;
        }

        if ($this->limit) {
            $partes[] = "LIMIT (" . $this->limit . ")";
        }

        

        return join(' ', $partes);
    }

    public function execute()
    {
        $query = $this->__toString();
        return (string) $query;
    }

    /**
     * [replace se encarga de buscar en una cadena una coincidencia y la remplaza por otro valor]
     * @param  [type] $search  [lo que se busca]
     * @param  [type] $replace [valor que remplaza al que esta establecido]
     * @param  [type] $string  [cadena a modificar]
     * @return [string]          [cadena formateada]
     */
    private function replace($search, $replace, $string): string
    {
        $index = strrpos($string, $search);
        if ($index !== false) {
            $text = substr_replace($string, $replace, $index, strlen($search));
        }
        return $text;
    }

}
