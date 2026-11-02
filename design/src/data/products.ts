import type { Product } from '../types/product';
import { images } from './images';

export const products: Product[] = [
{ code: 'CE-001', name: 'Cincin Filigree Minang', category: 'Cincin', metal: 'Emas', weight: 3.2, karat: '22K', size: '14', model: 'Filigree', price: 5280000, stock: 8, image: images.cincinEmas },
{ code: 'CE-002', name: 'Cincin Polos Klasik', category: 'Cincin', metal: 'Emas', weight: 2.5, karat: '18K', size: '12', model: 'Polos', price: 3375000, stock: 2, image: images.cincinEmas },
{ code: 'CP-001', name: 'Cincin Perak Solitaire', category: 'Cincin', metal: 'Perak', weight: 4.5, karat: 'Perak 925', size: '16', model: 'Solitaire', price: 385000, stock: 14, image: images.cincinPerak },
{ code: 'KE-001', name: 'Kalung Rantai Liontin', category: 'Kalung', metal: 'Emas', weight: 8.0, karat: '22K', size: '45 cm', model: 'Rantai Italy', price: 13200000, stock: 5, image: images.kalungEmas },
{ code: 'KE-002', name: 'Kalung Nori Halus', category: 'Kalung', metal: 'Emas', weight: 5.4, karat: '18K', size: '42 cm', model: 'Nori', price: 7290000, stock: 0, image: images.kalungEmas },
{ code: 'GE-001', name: 'Gelang Ukir Bunga', category: 'Gelang', metal: 'Emas', weight: 12.5, karat: '22K', size: 'Ø 6 cm', model: 'Keroncong Ukir', price: 20625000, stock: 3, image: images.gelangEmas },
{ code: 'GE-002', name: 'Gelang Bangle Polos', category: 'Gelang', metal: 'Emas', weight: 10.0, karat: '22K', size: 'Ø 6,2 cm', model: 'Bangle', price: 16500000, stock: 1, image: images.gelangEmas },
{ code: 'AE-001', name: 'Anting Tetes Filigree', category: 'Anting', metal: 'Emas', weight: 2.4, karat: '18K', size: 'Tusuk', model: 'Drop', price: 3240000, stock: 11, image: images.antingEmas },
{ code: 'AE-002', name: 'Anting Jepit Anak', category: 'Anting', metal: 'Emas', weight: 1.2, karat: '17K', size: 'Jepit', model: 'Mini', price: 1450000, stock: 6, image: images.antingEmas },
{ code: 'CC-001', name: 'Cincin Pasangan Ukir Nama', category: 'Cincin', metal: 'Emas', weight: 6.5, karat: '18K', size: '12 & 18', model: 'Couple', price: 8775000, stock: 4, image: images.cincinPasangan },
{ code: 'LP-001', name: 'Liontin Perak Rumah Gadang', category: 'Liontin', metal: 'Perak', weight: 3.0, karat: 'Perak 925', size: '2,5 cm', model: 'Rumah Gadang', price: 295000, stock: 0, image: images.cincinPerak },
{ code: 'GP-001', name: 'Gelang Perak Rantai', category: 'Gelang', metal: 'Perak', weight: 9.0, karat: 'Perak 925', size: '18 cm', model: 'Rantai', price: 520000, stock: 9, image: images.cincinPerak }];


export const showcaseProducts = [
{ name: 'Cincin Emas', detail: ['3,2 gr', '22K · 91,6%', 'Ukuran 14'], metal: 'Emas', price: 5280000, image: images.cincinEmas, description: 'Cincin filigree khas Minang dengan detail anyaman halus buatan pengrajin lokal Padang.' },
{ name: 'Cincin Perak', detail: ['4,5 gr', 'Perak 925', 'Ukuran 16'], metal: 'Perak', price: 385000, image: images.cincinPerak, description: 'Cincin perak sterling dengan batu solitaire, cocok untuk dipakai sehari-hari.' },
{ name: 'Kalung Emas', detail: ['8,0 gr', '22K · 91,6%', '45 cm'], metal: 'Emas', price: 13200000, image: images.kalungEmas, description: 'Kalung rantai Italy dengan liontin ukir, kilau emas kuning yang hangat.' },
{ name: 'Gelang Emas', detail: ['12,5 gr', '22K · 91,6%', 'Ø 6 cm'], metal: 'Emas', price: 20625000, image: images.gelangEmas, description: 'Gelang keroncong dengan ukiran bunga, investasi sekaligus perhiasan warisan.' },
{ name: 'Anting Emas', detail: ['2,4 gr', '18K · 75%', 'Tusuk'], metal: 'Emas', price: 3240000, image: images.antingEmas, description: 'Anting tetes filigree yang ringan dan anggun untuk acara resmi maupun harian.' },
{ name: 'Cincin Pasangan Custom', detail: ['Mulai 6 gr', '18K / 22K', 'Sesuai pesanan'], metal: 'Emas', price: 8775000, image: images.cincinPasangan, description: 'Cincin nikah dan tunangan dengan ukiran nama pasangan, dibuat sesuai ukuran Anda.' }] as
const;