<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Database query builder for UPDATE statements. See [Query Builder](/database/query/builder) for usage and examples.
 *
 * @package    Kohana/Database
 * @category   Query
 * @author     Kohana Team
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Kohana_Database_Query_Builder_Update extends Database_Query_Builder_Where {

	// UPDATE ...
	protected $_table;

	// SET ...
	protected $_set = array();

	/**
	 * Set the table for a update.
	 *
	 * @param   mixed  table name or array($table, $alias) or object
	 * @return  void
	 */
	public function __construct($table = NULL)
	{
		if ($table)
		{
			// Set the inital table name
			$this->_table = $table;
		}

		// Start the query with no SQL
		return parent::__construct(Database::UPDATE, '');
	}

	/**
	 * Sets the table to update.
	 *
	 * @param   mixed  table name or array($table, $alias) or object
	 * @return  $this
	 */
	public function table($table)
	{
		$this->_table = $table;

		return $this;
	}

	/**
	 * Set the values to update with an associative array.
	 *
	 * @param   array   associative (column => value) list
	 * @return  $this
	 */
	public function set(array $pairs)
	{
		foreach ($pairs as $column => $value)
		{
			$this->_set[] = array($column, $value);
		}

		return $this;
	}

	/**
	 * Set the value of a single column.
	 *
	 * @param   mixed  table name or array($table, $alias) or object
	 * @param   mixed  column value
	 * @return  $this
	 */
	public function value($column, $value)
	{
		$this->_set[] = array($column, $value);

		return $this;
	}

	/**
	 * Compile the SQL query and return it.
	 *
	 * @param   object  Database instance
	 * @return  string
	 */
	public function compile(Database $db)
	{
		// Start an update query
		$query = 'UPDATE '.$db->quote_table($this->_table);

		// Add the columns to update
		$query .= ' SET '.$this->_compile_set($db, $this->_set);

		if ( ! empty($this->_where))
		{
			// Add selection conditions
			$query .= ' WHERE '.$this->_compile_conditions($db, $this->_where);
		}

		if ($this->_limit !== NULL)
		{
			// Add limiting
			$query .= ' LIMIT '.$this->_limit;
		}

		return $query;
	}

	public function reset()
	{
		$this->_table = NULL;

		$this->_set   =
		$this->_where = array();

		$this->_limit = NULL;

		$this->_parameters = array();

		return $this;
	}


} // End Database_Query_Builder_Update
