<?php
namespace Composer\Installers;

class PhpBBInstaller extends BaseInstaller
{
    protected $locations = array(
        'extension'          => 'ext/{$vendor}/{$name}/',
        'extension-language' => 'ext/{$vendor}/language/{$name}/',
        'extension-style'    => 'ext/{$vendor}/styles/{$name}/',
        'language'           => 'language/{$name}/',
        'style'              => 'styles/{$name}/',
    );

    /**
     * Rewrote name and vendor for extension style and language
     * Template name expected name:
     *      phpbb-extension-language: <OriginalVendor>/<DestVendor-DestExtensionName-language-IsoCode>
     *      phpbb-extension-style: <OriginalVendor>/<DestVendor-DestExtensionName-style-StyleName>
     * Examples:
     *      phpbb-extension-language: foo/phpbb-acme-language-fr
     *      phpbb-extension-style: foo/phpbb-acme-style-my_style
     *
     * @param array $vars
     * @return array
     */
    public function inflectPackageVars($vars)
    {
        if ($vars['type'] === 'extension-language')
        {
            $pattern = '#^([a-z0-9]+)-([a-z0-9]+)-language-(.+)$#';
            return $this->inflectVars($vars, 'language', $pattern);
        }

        if ($vars['type'] === 'extension-style')
        {
            $pattern = '#^([a-z0-9]+)-([a-z0-9]+)-style-(.+)$#';
            return $this->inflectVars($vars, 'style', $pattern);
        }

        return $vars;
    }

    protected function inflectVars($vars, $type, $pattern)
    {
        $extra = $this->package->getExtra();

        if (preg_match($pattern, $vars['name'], $matches))
        {
            $vars['vendor'] = isset($extra['phpbb-extension']) ? $extra['phpbb-extension'] : $matches[1] . '/' . $matches[2];
            $vars['name'] = isset($extra['phpbb-' . $type]) ? $extra['phpbb-' . $type] : $matches[3];
        }

        return $vars;
    }
}
