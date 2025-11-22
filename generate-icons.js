import sharp from 'sharp';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const inputImage = path.join(__dirname, 'public/images/icone.png');
const outputDir = path.join(__dirname, 'public');

// Créer les icônes PWA
const sizes = [
  { size: 192, name: 'icon-192x192.png' },  // Mobile
  { size: 512, name: 'icon-512x512.png' }   // Desktop/Splash
];

async function generateIcons() {
  console.log('🎨 Génération des icônes PWA...\n');

  for (const { size, name } of sizes) {
    try {
      await sharp(inputImage)
        .resize(size, size, {
          fit: 'contain',
          background: { r: 255, g: 255, b: 255, alpha: 1 }
        })
        .png()
        .toFile(path.join(outputDir, name));
      
      console.log(`✅ ${name} créée (${size}x${size})`);
    } catch (error) {
      console.error(`❌ Erreur pour ${name}:`, error.message);
    }
  }

  console.log('\n🎉 Génération terminée !');
}

generateIcons();
