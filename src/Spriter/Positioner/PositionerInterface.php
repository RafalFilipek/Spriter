<?php

/**
 * SPRITER
 *
 * (c) Rafał Filipek <rafal.filipek@firma.o2.pl>
 *
 */

namespace Spriter\Positioner;

interface PositionerInterface
{

	/**
	 * Konstrukto zawsze jako argument musi przyjmować tablicę zawierająća zmienne konfiguracyjne niezbędne przy procesie ustawiania elementów.
	 * @param array $options tablica ze zmiennymi konfiguracyjnymi
	 */
	public function __construct(array $options = array());

	public function setElements(array $elements);

	/**
	 * Metoda zwracająca instancję obiektu Imagine\Image\Box zawierająca informacje o planowanym rozmiarze sprite'a
	 * @return Imagine\Image\Box
	 */
	public function calculateSize();

	/**
	 * Metoda ustawiająca obrazy na sprite'ie.
	 * @return array tablica w której kolejne klucze to pełne ścieżki do plików ustawione w metodzie `setElements` a wartości to instancje
	 * obiektu Imagine\Image\Point przechowujące informacje o położeniu elementu
	 */
	public function process();
}
