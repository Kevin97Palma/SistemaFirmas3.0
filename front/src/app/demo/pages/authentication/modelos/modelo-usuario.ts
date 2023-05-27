export class ModeloUsuario {
    codigo?:number
    identificacion!:string
    nombres!:string
    apellidos!:string
    correo!:string
    telefono!:string
    usuario!:string
    contrasena!:string
    rol!:number
    btnGuardar:boolean = true
    btnActualizar:boolean = false
}