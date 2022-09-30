export interface Response {
    result: string;
}

export interface ResponseDispositivos {
    result: string;
    dispositivos: Dispositivos[];
}
export interface Dispositivos {
    id: string;
    alias: string;
    serie: string;
}

export interface ResponseUsuario {
    result: string;
    usuario: Usuario;
}
export interface Usuario {
    email: string;
    estado: string;
    fecha_registro: string;
    id: string;
    imagen: string;
    nombre: string;
    rol: string;
}

export interface ResponseToken {
    result: string;
    token: string;
}