<?php

// Constantes pour la gestion des cookies
const COOKIE_LIFETIME = 60 * 60 * 24 * 30; // 30 jours
const COOKIE_NAME = 'language';
const DEFAULT_LANGUAGE = 'fr';

// Récupération de la préférence utilisateur depuis le cookie
$language = $_COOKIE[COOKIE_NAME] ?? DEFAULT_LANGUAGE;