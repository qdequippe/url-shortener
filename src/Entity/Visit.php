<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VisitRepository")
 */
class Visit
{
    use TimestampableEntity;

    public const BROWSERS_LIST = ['Internet Explorer', 'Firefox', 'Chrome', 'Opera', 'Safari', 'Microsoft Edge'];
    public const OS_LIST = ['Windows', 'Mac', 'Linux', 'Android', 'iOS'];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Link")
     * @ORM\JoinColumn(nullable=false)
     */
    private $link;

    /**
     * @ORM\Column(type="integer")
     */
    private $total = 0;

    /**
     * @ORM\Column(type="json_array")
     */
    private $referrers = [];

    /**
     * @ORM\Column(type="json_array")
     */
    private $countries = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $brChrome = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $brEdge = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $brFirefox = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $brIe = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $brOpera = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $brOther = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $brSafari = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $osAndroid = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $osIos = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $osLinux = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $osMacos = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $osOther = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $osWindows = 0;

    public function incBrowser(string $browserName): self
    {
        $browserToInc = 'Other';

        foreach (self::BROWSERS_LIST as $browser) {
            if (strtolower($browserName) === strtolower($browser)) {
                $browserToInc = ucwords($browser);

                break;
            }
        }

        $func = 'incBr'.$browserToInc;
        $this->$func();

        return $this;
    }

    public function incOs(string $osName): self
    {
        $osToInc = 'Other';

        foreach (self::OS_LIST as $os) {
            if (strtolower($osName) === strtolower($os)) {
                $osToInc = ucwords($os);

                break;
            }
        }

        $osToInc = preg_replace('/\s/i', '', $osToInc);

        $func = 'incOs'.$osToInc;
        $this->$func();

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLink(): ?Link
    {
        return $this->link;
    }

    public function setLink(?Link $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function incTotal(): self
    {
        $this->total++;

        return $this;
    }

    public function getReferrers(): ?array
    {
        return $this->referrers;
    }

    public function setReferrers(array $referrers): self
    {
        $this->referrers = $referrers;

        return $this;
    }

    public function addReferrer(string $referrer): self
    {
        $referrer = preg_replace('/\./i', '', $referrer);

        if (isset($this->referrers[$referrer])) {
            $this->referrers[$referrer]++;

            return $this;
        }

        $this->referrers[$referrer] = 1;

        return $this;
    }

    public function getCountries(): ?array
    {
        return $this->countries;
    }

    public function setCountries(array $countries): self
    {
        $this->countries = $countries;

        return $this;
    }

    public function addCountry(string $country): self
    {
        if (isset($this->countries[$country])) {
            $this->countries[$country]++;

            return $this;
        }

        $this->countries[$country] = 1;

        return $this;
    }

    public function getBrChrome(): ?int
    {
        return $this->brChrome;
    }

    public function setBrChrome(int $brChrome): self
    {
        $this->brChrome = $brChrome;

        return $this;
    }

    public function incBrChrome(): self
    {
        $this->brChrome++;

        return $this;
    }

    public function getBrEdge(): ?int
    {
        return $this->brEdge;
    }

    public function setBrEdge(int $brEdge): self
    {
        $this->brEdge = $brEdge;

        return $this;
    }

    public function incBrMicrosoftEdge(): self
    {
        $this->brEdge++;

        return $this;
    }

    public function getBrFirefox(): ?int
    {
        return $this->brFirefox;
    }

    public function incBrFirefox(): self
    {
        $this->brFirefox++;

        return $this;
    }

    public function setBrFirefox(int $brFirefox): self
    {
        $this->brFirefox = $brFirefox;

        return $this;
    }

    public function getBrIe(): ?int
    {
        return $this->brIe;
    }

    public function setBrIe(int $brIe): self
    {
        $this->brIe = $brIe;

        return $this;
    }

    public function incBrInternetExplorer(): self
    {
        $this->brIe++;

        return $this;
    }

    public function getBrOpera(): ?int
    {
        return $this->brOpera;
    }

    public function setBrOpera(int $brOpera): self
    {
        $this->brOpera = $brOpera;

        return $this;
    }

    public function incBrOpera(): self
    {
        $this->brOpera++;

        return $this;
    }

    public function getBrOther(): ?int
    {
        return $this->brOther;
    }

    public function setBrOther(int $brOther): self
    {
        $this->brOther = $brOther;

        return $this;
    }

    public function incBrOther(): self
    {
        $this->brOther++;

        return $this;
    }

    public function getBrSafari(): ?int
    {
        return $this->brSafari;
    }

    public function setBrSafari(int $brSafari): self
    {
        $this->brSafari = $brSafari;

        return $this;
    }

    public function incBrSafari(): self
    {
        $this->brSafari++;

        return $this;
    }

    public function getOsAndroid(): ?int
    {
        return $this->osAndroid;
    }

    public function setOsAndroid(int $osAndroid): self
    {
        $this->osAndroid = $osAndroid;

        return $this;
    }

    public function incOsAndroid(): self
    {
        $this->osAndroid++;

        return $this;
    }

    public function getOsIos(): ?int
    {
        return $this->osIos;
    }

    public function setOsIos(int $osIos): self
    {
        $this->osIos = $osIos;

        return $this;
    }

    public function incOsIos(): self
    {
        $this->osIos++;

        return $this;
    }

    public function getOsLinux(): ?int
    {
        return $this->osLinux;
    }

    public function setOsLinux(int $osLinux): self
    {
        $this->osLinux = $osLinux;

        return $this;
    }

    public function incOsLinux(): self
    {
        $this->osLinux++;

        return $this;
    }

    public function getOsMacos(): ?int
    {
        return $this->osMacos;
    }

    public function setOsMacos(int $osMacos): self
    {
        $this->osMacos = $osMacos;

        return $this;
    }

    public function incOsMac(): self
    {
        $this->osMacos++;

        return $this;
    }

    public function getOsOther(): ?int
    {
        return $this->osOther;
    }

    public function setOsOther(int $osOther): self
    {
        $this->osOther = $osOther;

        return $this;
    }

    public function incOsOther(): self
    {
        $this->osOther++;

        return $this;
    }

    public function getOsWindows(): ?int
    {
        return $this->osWindows;
    }

    public function setOsWindows(int $osWindows): self
    {
        $this->osWindows = $osWindows;

        return $this;
    }

    public function incOsWindows(): self
    {
        $this->osWindows++;

        return $this;
    }
}
