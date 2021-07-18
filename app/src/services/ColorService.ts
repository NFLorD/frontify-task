import Color from '@/models/Color';
import { Component, Vue } from 'vue-property-decorator';
import hexSorter from 'hexsorter';

@Component
export default class ColorService extends Vue {
    data(): Record<string, unknown> {
        return {
            colors: []
        };
    }

    async created(): Promise<void> {
        this.colors = await fetch('https://localhost/api/color?size=100')
            .then(response => response.json())
            .then(json => 
                json.items
                    .map(item => {
                        const { id, name, hex } = item;
                        return new Color(id, name, hex);
                    })
                    .sort(this.sortColors)
            );
    }
    
    async create({ name, hex }: { name: string, hex: string }): Promise<void> {
        const opts = {
            method: 'POST',
            body: JSON.stringify({ name, hex })
        };

        const response = await fetch('https://localhost/api/color', opts);
        const json = await response.json();

        if (json.error) {
            throw new Error(json.error);
        }
        
        this.colors.push(new Color(json.id, name, hex));
        this.colors.sort(this.sortColors);
    }

    async delete(color: Color, index: number): Promise<void> {
        const success = await fetch(`https://localhost/api/color/${color.id}`, { method: 'DELETE' })
            .then(response => response.status === 204);
        
        if (success) {
            this.colors.splice(index, 1);
        }
    }
    
    static hexToHsl(hex: string): Record<string, number>
    {
        const rgb = hex.match(/.{2}/g);
        const r = parseInt(rgb[0], 16);
        const g = parseInt(rgb[1], 16);
        const b = parseInt(rgb[2], 16);
    
        const max = Math.max(r, g, b);
        const min = Math.min(r, g, b);
    
        let h = 0;
        const l = (max + min) / 2;
        const d = max - min;

        let s;
    
        if (d == 0) {
            h = s = 0; // achromatic
        } else {
            s = d / (1 - Math.abs(2 * l - 1));
    
            switch (max) {
                case r:
                    h = 60 * (((g - b) / d) % 6);
                    if (b > g) {
                        h += 360;
                    }
                    break;
    
                case g:
                    h = 60 * ((b - r) / d + 2);
                    break;
    
                case b:
                    h = 60 * ((r - g) / d + 4);
                    break;
            }
        }
        return {
            h: Math.round(100 * h) / 100, 
            s: Math.round(100 * s) / 100, 
            l: Math.round(100 * l) / 100
        };
    }

    static huesAreinSameInterval(hue1: number, hue2: number, interval = 30) {
        const a1 = hue1 / interval;
        const a2 = a1 > 0 ? Math.floor(a1) : Math.ceil(a1);
        const b1 = hue2 / interval;
        const b2 = b1 > 0 ? Math.floor(b1) : Math.ceil(b1);
        return a2 === b2
    }

    sortColors(a: Color, b: Color): number {
        // https://www.generacodice.com/en/articolo/263211/sorting-by-color
        const hslA = ColorService.hexToHsl(a.hex);
        const hslB = ColorService.hexToHsl(b.hex);

        if(!ColorService.huesAreinSameInterval(hslA['h'], hslB['h'])) {
            if (hslA['h'] < hslB['h'])
                return 1;
            if (hslA['h'] > hslB['h'])
                return -1;
        }

        if (hslA['s'] < hslB['s'])
            return -1;
        if (hslA['s'] > hslB['s'])
            return 1;
        if (hslA['l'] < hslB['l'])
            return -1;
        if (hslA['l'] > hslB['l'])
            return 1;

        return 0;
    }
}