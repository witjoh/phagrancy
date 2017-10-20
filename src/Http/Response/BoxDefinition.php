<?php

/**
 * @file
 * Contains Phagrancy\Http\Response\BoxDefinition
 */

namespace Phagrancy\Http\Response;

use Phagrancy\Model\Entity;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Slim\Http\Response;

/**
 * Box JSON definition response
 *
 * @package Phagrancy\Http\Response
 */
class BoxDefinition
	extends Response
{
	use RespondsWithJson;

	public function __construct(Entity\Box $box, UriInterface $uri)
	{
		$json = [
			'name'      => $box->path(),
			'versions' => []
		];
		foreach ($box->versions() as $version => $providers) {
			if (empty($providers)) {
				continue;
			}
			$vpbs = [];
			foreach ($providers as $provider) {
				$vpbs[] = [
					'name' => $provider,
					'url'  => (string)$uri->withPath("/{$box->path()}/{$version}/{$provider}")
				];
			}
			$json['versions'][] = [
				'version'   => $version,
				'providers' => $vpbs
			];
		}
		$this->constructResponse($json);
	}
}