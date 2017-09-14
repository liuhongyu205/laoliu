<?php

namespace houdunwang\model;

use PDO;
use PDOException;
use Exception;

class Base
{
	/**
	 * 声明操作数据表
	 *
	 * @var string
	 */
	protected $table;
	/**
	 * pdo对象
	 *
	 * @var null
	 */
	private static $pdo = null;
	/**
	 * where条件
	 *
	 * @var
	 */
	private $where = '';
	/**
	 * 存放查询结构的数据
	 *
	 * @var
	 */
	private $data;
	/**
	 * 获取指定字段
	 *
	 * @var
	 */
	private $field = '';
    private $order = '';

	/**
	 * Base constructor.
	 *
	 * @param $class
	 */
	public function __construct ( $class )
	{
		//数据库连接
		if ( is_null ( self::$pdo ) ) {
			$this->connect ();
		}
		$info        = explode ( '\\' , $class );
		$this->table = strtolower ( $info[ 2 ] );
	}

	/**
	 * @throws Exception
	 */
	private function connect ()
	{
		try {
			$dsn       = "mysql:host=localhost;dbname=liu";
			$user      = "root";
			$password  = "root";
			self::$pdo = new PDO( $dsn , $user , $password );
			self::$pdo->query ( 'set names utf8' );
			self::$pdo->setAttribute ( PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION );
		} catch ( PDOException $e ) {
			throw new Exception( $e->getMessage () );
		}
	}

	/**
	 * 统计数据
	 * @return mixed	数据总数
	 */
	public function count(){
		$sql = "select count(*) as total from {$this->table} {$this->where}";
		//执行sql语句
		$data =  $this->query ($sql);
		return $data[0]['total'];
	}

	/**
	 * 获取制定字段
	 *
	 * @param $field ('title,time')
	 *
	 * @return $this
	 */
	public function field ( $field )
	{
		$this->field = $field;

		return $this;
	}

	/**
	 * 执行数据写入
	 *
	 * @param $data    要写入的数据
	 *
	 * @return mixed
	 */
	public function insert ( $data )
	{
		$fields = '';
		$values = '';
		foreach ( $data as $k => $v ) {
			$fields .= $k . ',';
			if ( is_int ( $v ) ) {
				$values .= $v . ',';
			} else {
				$values .= "'$v'" . ',';
			}
		}
		$fields = rtrim ( $fields , ',' );
		$values = rtrim ( $values , ',' );
		//dd($fields);
		//dd($values);
		$sql = "insert into {$this->table} ({$fields}) values ({$values})";

		//执行sql语句
		return $this->exec ( $sql );
	}

	/**
	 * 执行更新数据
	 *
	 * @param array $data 更新的数据
	 *
	 * @return bool|mixed    受影响条数
	 */
	public function update ( array $data )
	{
		//如果没有指定where条件不允许更新数据
		if ( empty( $this->where ) )
			return false;
		//dd($data);die;
		//声明空字符串，用来存储重组完成的结果：title='后盾网',time=10
		$fields = '';
		foreach ( $data as $k => $v ) {
			if ( is_int ( $v ) ) {
				$fields .= "$k=$v" . ',';
			} else {
				$fields .= "$k='$v'" . ',';
			}
		}
		//最后侧,去掉
		$fields = rtrim ( $fields , ',' );
		//dd($fields);
		$sql = "update {$this->table} set {$fields} {$this->where}";

		//执行sql语句
		return $this->exec ( $sql );
	}

	/**
	 * 删除数据
	 *
	 * @param string $pk 删除主键值
	 *
	 * @return bool|mixed    受影响条数
	 */
	public function destory ( $pk = '' )
	{
		if ( empty( $this->where ) || empty( $pk ) ) {
			if ( empty( $this->where ) ) {
				//获取主键
				$priKey = $this->getPriKey ();
				//这个时候说明没有where条件
				//那么把destory传入参数作为where条件
				$this->where ( "{$priKey}={$pk}" );
			}
			$sql = "delete from {$this->table} {$this->where}";

			//执行sql语句
			return $this->exec ( $sql );
		} else {
			return false;
		}
	}

	/**
	 * 获取所有数据
	 *
	 * @return $this|array
	 */
	public function getAll ()
	{
		$field = $this->field ? : '*';
		//组合查询所有数据的sql语句
		$sql = "select {$field} from {$this->table} {$this->where}";
		//调用自定义的query查询
		$data = $this->query ( $sql );
		if ( ! empty( $data ) ) {
			$this->data = $data;

			return $this;
		}

		return [];
	}

	/**
	 * 根据主键查找一条数据
	 *
	 * @param $pk    主键值
	 */
	public function find ( $pk )
	{
		//获取当前操作表的主键
		$priKey = $this->getPriKey ();
		//dd($priKey);
		//$sql = "select * from 表 where 主键=$pk";
		//组合where语句,调用where方法
		//为了把sql中where条件语句存储到where属性中
		$this->where ( "$priKey={$pk}" );
		$field = $this->field ? : '*';
		$sql = "select {$field} from {$this->table} {$this->where}";
		//调用我们自定义的query方法执行sql语句
		$data = $this->query ( $sql );
		if ( ! empty( $data ) ) {
			$this->data = current ( $data );

			return $this;
		}

		return $this;

		return [];
	}

	/**
	 * 将对象转为数组
	 *
	 * @return array    转之后的数组
	 */
	public function toArray ()
	{
		if ( $this->data ) {
			return $this->data;
		}

		return [];
	}

	/**
	 * sql查询语句中where条件
	 *
	 * @param $where    where条件
	 *
	 * @return $this
	 */
	public function where ( $where )
	{
		//$this->where = "where sex='女' and age>20";
		$this->where = "where {$where}";

		return $this;
	}

	/**
	 * 获取主键
	 *
	 * @return string    主键字段
	 */
	public function getPriKey ()
	{
		//组合sql语句，为了查看表结构
		//为了在里面呢找主键
		$sql = "desc " . $this->table;
		//调用自定义的query方法进行查询
		$data = $this->query ( $sql );
		//dd($data);
		$priKey = '';//定义空字符串用来存储主键
		foreach ( $data as $v ) {
			if ( $v[ 'Key' ] == 'PRI' ) {
				//说明是主键
				$priKey = $v[ 'Field' ];
				break;
			}
		}

		return $priKey;
	}

	/**
	 * 执行查询
	 *
	 * @param $sql    查询的sql语句
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function query ( $sql )
	{
		try {
			//调用pdoquery
			$res = self::$pdo->query ( $sql );
			return $res->fetchAll ( PDO::FETCH_ASSOC );
		} catch ( PDOException $e ) {
//			throw new Exception( $e->getMessage () );
		}
	}

	/**
	 * 执行没有结果集的sql
	 *
	 * @param $sql    sql语句
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function exec ( $sql )
	{
		try {
			$res = self::$pdo->exec ( $sql );
			//如果是添加的话，获取返回的自增主键值
			if ( $lastInsertId = self::$pdo->lastInsertId () ) {
				//说明有返回的自增id
				return $lastInsertId;
			}

			return $res;
		} catch ( PDOException $e ) {
			throw new Exception( $e->getMessage () );
		}
	}
    public function order($order){
        //order by gid desc
        $this->order= "order by " . $order;
        return $this;
    }
}