<?php
namespace Project\Model;

class ProjectEntity
{
	protected $id;
	protected $name;
	protected $description;
	protected $minimum_contribution;
	protected $eos_public_address;
	protected $created_datetime;
	protected $created_user_id;

	public function __construct()
	{
		$this->created_datetime = date('Y-m-d H:i:s');
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId($value)
	{
		$this->id = $value;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($value)
	{
		$this->name = $value;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setDescription($value)
	{
		$this->description = $value;
	}

	public function getMinimumContribution()
	{
		return $this->minimum_contribution;
	}

	public function setMinimumContribution($value)
	{
		$this->minimum_contribution = $value;
	}

	public function getEosPublicAddress()
	{
		return $this->eos_public_address;
	}

	public function setEosPublicAddress($value)
	{
		$this->eos_public_address = $value;
	}

	public function getCreatedDatetime()
	{
		return $this->created_datetime;
	}

	public function setCreatedDatetime($value)
	{
		$this->created_datetime = $value;
	}
}
