<?php
namespace Edu\Cnm\GitHubBrowser;

/**
 * Container for GitHub API Result
 *
 * Container for the GitHub Tree result, which performs a depth first search of a repository's tree or subtree
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @see https://developer.github.com/v3/git/trees/ GitHub Trees
 **/
class GitHubEntity implements \JsonSerializable {
	/**
	 * path relative to the root of repository
	 * @var string $path
	 **/
	private $path;
	/**
	 * permissions for the entity
	 * @var int $mode
	 **/
	private $mode;
	/**
	 * SHA1 signature for the entity, as a string of hexits
	 * @var string $sha
	 **/
	private $sha;
	/**
	 * file size of this entity, in bytes
	 * @var int $size
	 **/
	private $size;
	/**
	 * type of entity; can be one of blob, commit, or tree
	 * @var string $type
	 **/
	private $type;
	/**
	 * URL for this entity
	 * @var string $url
	 **/
	private $url;

	/**
	 * constructor for this entity
	 *
	 * the parameters may be passed as one of two formats:
	 * - object: {"path" : $newPath, "mode" : $newMode, "sha" : $newSha, "size" : $newSize, "type" : $newType, "url" : $newUrl}
	 * - parameters one at time, mirroring the array above
	 * if the parameters are not passed in either format, a BadMethodCallException is thrown
	 *
	 * @throws \BadMethodCallException if the constructor is called in an invalid manner
	 * @throws \InvalidArgumentException if strings are insecure or empty
	 * @throws \RangeException if integers are out of range
	 **/
	public function __construct() {
		$parameters = ["path", "mode", "sha", "size", "type", "url"];
		$numArgs = func_num_args();
		if($numArgs === 1) {
			$object = func_get_arg(0);
			if(gettype($object) !== "object") {
				throw(new \BadMethodCallException("single argument must be an object"));
			}
			$stateVariables = get_object_vars($object);
			foreach($stateVariables as $stateVariable => $value) {
				${"new" .  ucfirst($stateVariable)} = $value;
			}
		} else if($numArgs === 6) {
			foreach($parameters as $index => $parameter) {
				${"new" . ucfirst($parameters[$index])} = func_get_arg($index);
			}
		} else {
			throw(new \BadMethodCallException("invalid number of parameters to constructor"));
		}

		try {
			$this->setPath($newPath);
			$this->setMode($newMode);
			$this->setSha($newSha);
			$this->setSize($newSize);
			$this->setType($newType);
			$this->setUrl($newUrl);
		} catch(\InvalidArgumentException $invalidArgument) {
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), $invalidArgument->getCode(), $invalidArgument));
		} catch(\RangeException $range) {
			throw(new \RangeException($range->getMessage(), $range->getCode(), $range));
		} catch(\Exception $exception) {
			throw(new \Exception($exception->getMessage(), $exception->getCode(), $exception));
		}
	}

	/**
	 * accessor method for path
	 *
	 * @return string current value of path
	 **/
	public function getPath() : string {
		return($this->path);
	}

	/**
	 * mutator method for path
	 *
	 * @param string $newPath new value of path
	 * @throws \InvalidArgumentException if path is invalid
	 **/
	public function setPath(string $newPath) {
		$newPath = trim($newPath);
		$newPath = filter_var($newPath, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

		if($newPath === false) {
			throw(new \InvalidArgumentException("path is insecure or empty"));
		}

		$this->path = $newPath;
	}

	/**
	 * accessor method for mode
	 *
	 * @return int current value of mode
	 **/
	public function getMode() : int {
		return($this->mode);
	}

	/**
	 * mutator method for mode
	 *
	 * @param int|string $newMode new value of mode
	 * @throws \InvalidArgumentException if mode is invalid
	 **/
	public function setMode($newMode) {
		if(gettype($newMode) === "integer") {
			$this->mode = $newMode;
		} else if (gettype($newMode) === "string") {
			$newMode = octdec($newMode);
			if($newMode !== 0) {
				$this->mode = $newMode;
			} else {
				throw(new \InvalidArgumentException("invalid mode"));
			}
		} else {
			throw(new \InvalidArgumentException("invalid mode"));
		}
	}

	/**
	 * accessor method for sha
	 *
	 * @return string current value of sha
	 **/
	public function getSha() : string {
		return($this->sha);
	}

	/**
	 * mutator method for sha
	 *
	 * @param string $newSha new value of sha
	 * @throws \InvalidArgumentException if sha is invalid
	 **/
	public function setSha(string $newSha) {
		$newSha = trim($newSha);
		if(ctype_xdigit($newSha) === false) {
			throw(new \InvalidArgumentException("invalid sha"));
		}
		if(strlen($newSha) !== 40) {
			throw(new \InvalidArgumentException("invalid sha"));
		}

		$this->sha = $newSha;
	}

	/**
	 * accessor method for size
	 *
	 * @return string current value of size
	 **/
	public function getSize() : int {
		return($this->size);
	}

	/**
	 * mutator method for size
	 *
	 * @param string $newSize new value of size
	 * @throws \RangeException if size is invalid
	 **/
	public function setSize(int $newSize) {
		if($newSize < 0) {
			throw(new \RangeException("invalid size"));
		}

		$this->size = $newSize;
	}

	/**
	 * accessor method for type
	 *
	 * @return string current value of type
	 **/
	public function getType() : string {
		return($this->type);
	}

	/**
	 * mutator method for type
	 *
	 * @param string $newType new value of type
	 * @throws \InvalidArgumentException if type is invalid
	 **/
	public function setType(string $newType) {
		$newType = trim($newType);
		$validTypes = ["blob", "commit", "tree"];

		if(in_array($newType, $validTypes) === false) {
			throw(new \InvalidArgumentException("invalid type"));
		}

		$this->type = $newType;
	}

	/**
	 * accessor method for url
	 *
	 * @return string current value of url
	 **/
	public function getUrl() : string {
		return($this->url);
	}

	/**
	 * mutator method for url
	 *
	 * @param string $newUrl new value of url
	 * @throws \InvalidArgumentException if url is invalid
	 **/
	public function setUrl(string $newUrl) {
		$newUrl = trim($newUrl);
		$newUrl = filter_var($newUrl, FILTER_SANITIZE_URL);

		if($newUrl === false) {
			throw(new \InvalidArgumentException("invalid url"));
		}

		$this->url = $newUrl;
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() {
		return(get_object_vars($this));
	}
}
