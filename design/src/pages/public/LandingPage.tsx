import React from 'react';
import { LandingNavbar } from '../../components/landing/LandingNavbar';
import { HeroSection } from '../../components/landing/HeroSection';
import { AboutSection } from '../../components/landing/AboutSection';
import { ProductShowcase } from '../../components/landing/ProductShowcase';
import { CustomOrderSection } from '../../components/landing/CustomOrderSection';
import { PromoSection } from '../../components/landing/PromoSection';
import { WhyUsSection } from '../../components/landing/WhyUsSection';
import { RegisterBanner } from '../../components/landing/RegisterBanner';
import { ContactSection } from '../../components/landing/ContactSection';
import { LandingFooter } from '../../components/landing/LandingFooter';
import { WhatsAppFab } from '../../components/landing/WhatsAppFab';

export function LandingPage() {
  return (
    <div className="w-full bg-ivory">
      <LandingNavbar />
      <main>
        <HeroSection />
        <AboutSection />
        <ProductShowcase />
        <CustomOrderSection />
        <PromoSection />
        <WhyUsSection />
        <RegisterBanner />
        <ContactSection />
      </main>
      <LandingFooter />
      <WhatsAppFab />
    </div>);

}